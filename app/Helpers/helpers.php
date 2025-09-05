<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

if (!function_exists('spaRender')) {
  function spaRender(Request $request, string $content, array $data = [])
  {
    if ($request->ajax()) {
      /** @var \Illuminate\View\View $view */
      $view = View::make($content, $data);
      $sections = $view->renderSections();

      return response()->json([
        'title' => $sections['title'] ?? '',
        'styles'  => $sections['styles'] ?? '',
        'content' => $sections['content'] ?? '',
        'scripts' => $sections['scripts'] ?? '',
      ]);
    } else {
      return view($content, $data);
    }
  }
}

if (!function_exists('formatSize')) {
  function formatSize($bytes, $precision = 2)
  {
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];

    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes, 1024) : 0));
    $pow = min($pow, count($units) - 1);

    $bytes /= (1 << (10 * $pow));

    return round($bytes, $precision) . $units[$pow];
  }
}
