<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Uploads extends BaseController
{
    // Allow most uploads; block potentially dangerous executable/script types.
    private array $blockedExtensions = ['php', 'phtml', 'phar', 'exe', 'bat', 'cmd', 'com', 'js', 'sh', 'ps1', 'html', 'htm'];
    private int $maxUploadMb = 25;

    public function index()
    {
        $uploadDir = WRITEPATH . 'uploads' . DIRECTORY_SEPARATOR;
        if (! is_dir($uploadDir)) {
            @mkdir($uploadDir, 0775, true);
        }

        $files = [];
        if (is_dir($uploadDir)) {
            $items = array_diff(scandir($uploadDir, SCANDIR_SORT_DESCENDING), ['.', '..', 'index.html']);
            foreach ($items as $name) {
                $path = $uploadDir . $name;
                if (! is_file($path)) {
                    continue;
                }
                $files[] = [
                    'name' => $name,
                    'size' => filesize($path) ?: 0,
                    'modified' => filemtime($path) ?: 0,
                ];
                if (count($files) >= 15) {
                    break;
                }
            }
        }

        return view('admin/uploads/index', [
            'title' => 'Upload Data',
            'page_title' => 'Upload Data',
            'files' => $files,
            'blockedExtensions' => $this->blockedExtensions,
            'maxUploadMb' => $this->maxUploadMb,
        ]);
    }

    public function store()
    {
        $file = $this->request->getFile('data_file');
        if (! $file || ! $file->isValid()) {
            return redirect()->back()->with('error', 'Please choose a valid file to upload.');
        }

        // Some browsers/servers may not provide a reliable extension via getExtension(),
        // so we fall back to the client-provided extension if needed.
        $ext = strtolower((string) $file->getExtension());
        if ($ext === '') {
            $ext = strtolower((string) $file->getClientExtension());
        }

        if ($ext !== '' && in_array($ext, $this->blockedExtensions, true)) {
            return redirect()->back()->with('error', 'This file type is not allowed.');
        }

        $sizeMb = (float) $file->getSizeByUnit('mb');
        if ($sizeMb > $this->maxUploadMb) {
            return redirect()->back()->with('error', 'File is too large. Max: ' . $this->maxUploadMb . ' MB');
        }

        $uploadDir = WRITEPATH . 'uploads';
        if (! is_dir($uploadDir)) {
            @mkdir($uploadDir, 0775, true);
        }

        $safeBase = preg_replace('/[^a-zA-Z0-9\-_]+/', '_', pathinfo($file->getName(), PATHINFO_FILENAME));
        $safeBase = trim((string) $safeBase, '_');
        if ($safeBase === '') {
            $safeBase = 'upload';
        }

        $newName = $safeBase . '_' . date('Ymd_His') . '.' . $ext;
        $file->move($uploadDir, $newName, true);

        return redirect()->to('admin/uploads')->with('success', 'File uploaded: ' . $newName);
    }

    public function delete()
    {
        $name = (string) $this->request->getPost('name');
        $name = trim($name);

        if ($name === '') {
            return redirect()->back()->with('error', 'Missing file name.');
        }

        // Prevent path traversal and only allow simple filenames.
        if (preg_match('/[\/\\\\]/', $name) === 1 || str_contains($name, '..')) {
            return redirect()->back()->with('error', 'Invalid file name.');
        }

        $uploadDir = WRITEPATH . 'uploads' . DIRECTORY_SEPARATOR;
        $path = $uploadDir . $name;

        if (! is_file($path)) {
            return redirect()->back()->with('error', 'File not found.');
        }

        if (! @unlink($path)) {
            return redirect()->back()->with('error', 'Failed to delete file.');
        }

        return redirect()->to('admin/uploads')->with('success', 'File deleted.');
    }
}

