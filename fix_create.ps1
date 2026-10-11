$file = "c:\xampp\htdocs\reseavh-office\app\Views\admin\researchers\create.php"
$content = Get-Content $file -Raw

# Pattern 1: old('field', $researcher['field'])  - basic pattern
$content = $content -replace ", \$researcher\['([a-zA-Z_]+)'\]", ""

# Pattern 2: old('field', $researcher['field'] ?? '')
$content = $content -replace ", \$researcher\['([a-zA-Z_]+)'\] \?\? ''", ""

# Pattern 3: old('field', isset($researcher['field']) ? preg_replace(...) : '')
$content = $content -replace ", isset\(\$researcher\['([a-zA-Z_]+)'\]\) \? preg_replace\([^)]+\) : ''", ""

Set-Content $file $content -NoNewline
Write-Output "Done"
