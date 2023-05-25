<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;


Route::post('/v1/short-urls', function (Request $request) {

    $url = $request->input('url');

    // Validación de la URL
    if (empty($url)) {
        return response()->json(['error' => 'La URL es requerida.'], Response::HTTP_BAD_REQUEST);
    }

    // Obtengor el acortador de URL utilizando la API pública de TinyURL
    $response = Http::get('https://tinyurl.com/api-create.php', ['url' => $url]);

    if ($response->failed()) {
        return response()->json(['error' => 'Fallo al acortar la URL'], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    $shortUrl = $response->body();

    return response()->json(['url' => $shortUrl], Response::HTTP_OK);
});
