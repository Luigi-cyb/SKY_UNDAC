<?php

/**
 * Generador de estructura de proyecto Laravel
 * Uso: php generate-structure.php > project-structure.txt
 */

class ProjectStructureGenerator
{
    private $ignoreDirs = [
        'vendor',
        'node_modules',
        'storage/framework',
        'storage/logs',
        '.git',
        'public/build',
        'public/hot',
    ];

    private $ignoreFiles = [
        '.env',
        '.env.example',
        'composer.lock',
        'package-lock.json',
        'yarn.lock',
    ];

    private $includeContent = [
        // Archivos importantes que quieres incluir completos
        'routes/web.php',
        'routes/api.php',
        'config/app.php',
        'composer.json',
        'package.json',
    ];

    public function generate($path = '.')
    {
        echo "# Estructura del Proyecto Laravel\n\n";
        echo "Generado: " . date('Y-m-d H:i:s') . "\n\n";
        
        echo "## Árbol de Directorios\n\n";
        echo "```\n";
        $this->printTree($path);
        echo "```\n\n";

        echo "## Archivos Importantes\n\n";
        $this->printImportantFiles($path);

        echo "\n## Modelos\n\n";
        $this->printModels($path);

        echo "\n## Controladores\n\n";
        $this->printControllers($path);

        echo "\n## Migraciones\n\n";
        $this->printMigrations($path);
    }

    private function printTree($dir, $prefix = '', $isLast = true)
    {
        $basename = basename($dir);
        
        if ($this->shouldIgnore($dir)) {
            return;
        }

        if ($prefix !== '') {
            echo $prefix . ($isLast ? '└── ' : '├── ') . $basename . "\n";
        } else {
            echo $basename . "/\n";
        }

        if (!is_dir($dir)) {
            return;
        }

        $items = scandir($dir);
        $items = array_diff($items, ['.', '..']);
        
        // Ordenar: directorios primero
        usort($items, function($a, $b) use ($dir) {
            $aIsDir = is_dir("$dir/$a");
            $bIsDir = is_dir("$dir/$b");
            if ($aIsDir === $bIsDir) return strcmp($a, $b);
            return $bIsDir ? 1 : -1;
        });

        $count = count($items);
        $i = 0;

        foreach ($items as $item) {
            $i++;
            $isLastItem = ($i === $count);
            $path = "$dir/$item";
            
            if ($this->shouldIgnore($path)) {
                continue;
            }

            $newPrefix = $prefix . ($prefix !== '' ? ($isLast ? '    ' : '│   ') : '');
            $this->printTree($path, $newPrefix, $isLastItem);
        }
    }

    private function printImportantFiles($basePath)
    {
        foreach ($this->includeContent as $file) {
            $fullPath = "$basePath/$file";
            if (file_exists($fullPath)) {
                echo "### $file\n\n";
                echo "```" . $this->getExtension($file) . "\n";
                echo file_get_contents($fullPath);
                echo "\n```\n\n";
            }
        }
    }

    private function printModels($basePath)
    {
        $modelsPath = "$basePath/app/Models";
        if (!is_dir($modelsPath)) {
            echo "No se encontraron modelos.\n";
            return;
        }

        $files = glob("$modelsPath/*.php");
        foreach ($files as $file) {
            $content = file_get_contents($file);
            $className = basename($file, '.php');
            
            echo "#### $className\n\n";
            
            // Extraer propiedades importantes
            if (preg_match('/protected\s+\$fillable\s*=\s*\[(.*?)\]/s', $content, $matches)) {
                echo "- **Fillable**: " . trim($matches[1]) . "\n";
            }
            if (preg_match('/protected\s+\$table\s*=\s*[\'"](.+?)[\'"]/s', $content, $matches)) {
                echo "- **Table**: " . $matches[1] . "\n";
            }
            
            // Extraer relaciones
            preg_match_all('/public\s+function\s+(\w+)\(\).*?(belongsTo|hasMany|hasOne|belongsToMany)/s', $content, $relations);
            if (!empty($relations[1])) {
                echo "- **Relaciones**:\n";
                foreach ($relations[1] as $idx => $relation) {
                    echo "  - `{$relation}()` → {$relations[2][$idx]}\n";
                }
            }
            echo "\n";
        }
    }

    private function printControllers($basePath)
    {
        $controllersPath = "$basePath/app/Http/Controllers";
        if (!is_dir($controllersPath)) {
            echo "No se encontraron controladores.\n";
            return;
        }

        $this->scanControllersRecursive($controllersPath, '');
    }

    private function scanControllersRecursive($dir, $namespace)
    {
        $files = scandir($dir);
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') continue;
            
            $path = "$dir/$file";
            if (is_dir($path)) {
                $this->scanControllersRecursive($path, $namespace . $file . '/');
            } elseif (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                $content = file_get_contents($path);
                $className = basename($file, '.php');
                
                echo "#### $namespace$className\n\n";
                
                // Extraer métodos públicos
                preg_match_all('/public\s+function\s+(\w+)\s*\((.*?)\)/s', $content, $methods);
                if (!empty($methods[1])) {
                    echo "- **Métodos**:\n";
                    foreach ($methods[1] as $idx => $method) {
                        if ($method !== '__construct') {
                            $params = trim($methods[2][$idx]);
                            echo "  - `{$method}(" . ($params ?: '') . ")`\n";
                        }
                    }
                }
                echo "\n";
            }
        }
    }

    private function printMigrations($basePath)
    {
        $migrationsPath = "$basePath/database/migrations";
        if (!is_dir($migrationsPath)) {
            echo "No se encontraron migraciones.\n";
            return;
        }

        $files = glob("$migrationsPath/*.php");
        sort($files);
        
        foreach ($files as $file) {
            $fileName = basename($file);
            echo "- `$fileName`\n";
        }
    }

    private function shouldIgnore($path)
    {
        $path = str_replace('\\', '/', $path);
        
        foreach ($this->ignoreDirs as $ignore) {
            if (strpos($path, $ignore) !== false) {
                return true;
            }
        }

        $basename = basename($path);
        if (in_array($basename, $this->ignoreFiles)) {
            return true;
        }

        return false;
    }

    private function getExtension($file)
    {
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        return match($ext) {
            'php' => 'php',
            'js' => 'javascript',
            'json' => 'json',
            default => ''
        };
    }
}

// Ejecutar
$generator = new ProjectStructureGenerator();
$generator->generate('.');