<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;


class BackupController extends Controller
{
    /*
     * Muestra la lista de respaldos de la aplicación disponibles. Itera sobre los archivos en el disco de respaldo, 
     * filtra solo los archivos ZIP y extrae el tamaño, nombre y fecha de la última modificación para mostrarlos.
    */
    public function backup()
    {
        $diskName = config('backup.backup.destination.disks')[0] ?? 'local';
        $disk = Storage::disk($diskName);
        $appName = config('backup.backup.name'); 

        $files = $disk->files($appName) ?? []; 
        
        $backups = [];

        foreach ($files as $file) {
            if (str_ends_with($file, '.zip')) { 
                if ($disk->exists($file)) {
                    $size = $disk->size($file) ?? 0;
                    $lastModified = $disk->lastModified($file) ?? time();

                    $backups[] = [
                        'file_path'     => $file,
                        'file_name'     => basename($file),
                        'file_size'     => $this->formatBytes($size),
                        'last_modified' => date('Y-m-d H:i:s', $lastModified),
                    ];
                }
            }
        }

        $backups = array_reverse($backups);

        return view('administrador.backups.manual', compact('backups'));
    }


    /*
     * Ejecuta el comando de Artisan para crear un nuevo respaldo. Utiliza el comando 'backup:run' del paquete 
     * Spatie para generar un respaldo de la base de datos (se usa la opción '--only-db').
    */
    public function createBackup()
    {
        try {
            Artisan::call('backup:run', ['--only-db' => true]);
            
            return redirect()->back()->with('success', '¡Respaldo de la base de datos creado exitosamente!');

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error al crear el respaldo: ' . $e->getMessage());
        }
    }


    /*
     * Restaura la base de datos desde un archivo de respaldo ZIP seleccionado. Extrae el archivo SQL del ZIP 
     * y utiliza el comando 'mysql' para importar los datos a la base de datos configurada en Laravel.
    */
    public function restoreBackup(Request $request)
    {
        $request->validate(['backup_file' => 'required|string']);

        try {
            $diskName = config('backup.backup.destination.disks')[0];
            $disk = Storage::disk($diskName);
            $appName = config('backup.backup.name');
            $fileName = $request->input('backup_file');
            $filePath = storage_path("app/private/{$appName}/{$fileName}");

            $zip = new \ZipArchive;
            if ($zip->open($filePath) === TRUE) {
                $extractPath = storage_path('app/temp_restore');
                if (!is_dir($extractPath)) {
                    mkdir($extractPath, 0755, true);
                }
                $zip->extractTo($extractPath);
                $zip->close();
            } else {
                throw new \Exception("No se pudo abrir el archivo ZIP.");
            }

            $sqlFile = glob($extractPath . '/db-dumps/*.sql')[0] ?? null;
            if (!$sqlFile) {
                throw new \Exception("No se encontró el archivo SQL dentro del respaldo.");
            }

            $dbConfig = config('database.connections.mysql');

            /*La ruta hardcodeada para 'mysql' (en XAMPP) debe ajustarse si se usa otro entorno.*/
            $mysqlPath = '/Applications/XAMPP/xamppfiles/bin/mysql';
            
            // $mysqlPath = 'C:\\xampp\\mysql\\bin\\mysql.exe';

            $command = sprintf(
                '"%s" --user=%s %s --host=%s %s < "%s"',
                $mysqlPath,
                $dbConfig['username'],
                $dbConfig['password'] ? '--password='.$dbConfig['password'] : '',
                $dbConfig['host'],
                $dbConfig['database'],
                $sqlFile
            );

            exec($command, $output, $resultCode);

            if ($resultCode !== 0) {
                throw new \Exception("Error al ejecutar la restauración. Código: {$resultCode}. Salida: " . implode("\n", $output));
            }

            return redirect()->back()->with('success', '¡Restauración completada exitosamente!');

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error durante la restauración: ' . $e->getMessage());
        }
    }

    /*
     * Convierte una cantidad de bytes en un formato legible (KB, MB, GB, TB).
    */
    private function formatBytes($bytes, $precision = 2) { 
        $units = ['B', 'KB', 'MB', 'GB', 'TB']; 
        $bytes = max($bytes, 0); 
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
        $pow = min($pow, count($units) - 1); 
        $bytes /= (1 << (10 * $pow)); 
        return round($bytes, $precision) . ' ' . $units[$pow]; 
    }
}