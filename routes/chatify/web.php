<?php

use Illuminate\Support\Facades\Route;
use Chatify\Http\Controllers\MessagesController as ChatifyMessagesController;
use App\Http\Controllers\ChatifyPatchController;

Route::prefix(config('chatify.routes.prefix', 'chatify'))
    ->middleware(['web', 'auth', 'restrict.chatify', 'chat.desktop'])
    ->group(function () {
        // Main app
        Route::get('/', [ChatifyMessagesController::class, 'index'])
            ->name(config('chatify.routes.prefix'));

        // Info por id (user/group)
        Route::post('/idInfo', [ChatifyMessagesController::class, 'idFetchData']);

        // Enviar y obtener mensajes
        Route::post('/sendMessage',   [ChatifyMessagesController::class, 'send'])->name('send.message');
        Route::post('/fetchMessages', [ChatifyMessagesController::class, 'fetch'])->name('fetch.messages');

        // Descargar adjuntos
        Route::get('/download/{fileName}', [ChatifyMessagesController::class, 'download'])
            ->name(config('chatify.attachments.download_route_name'));

        // Auth Pusher privado
        Route::post('/chat/auth', [ChatifyMessagesController::class, 'pusherAuth'])->name('pusher.auth');

        // Vistos, contactos y favoritos
        Route::post('/makeSeen',       [ChatifyMessagesController::class, 'seen'])->name('messages.seen');
        Route::get('/getContacts',     [ChatifyMessagesController::class, 'getContacts'])->name('contacts.get');
        Route::post('/updateContacts', [ChatifyMessagesController::class, 'updateContactItem'])->name('contacts.update');
        Route::post('/star',           [ChatifyMessagesController::class, 'favorite'])->name('star');
        Route::post('/favorites',      [ChatifyMessagesController::class, 'getFavorites'])->name('favorites');

        // Buscar & compartidos
        Route::get('/search',  [ChatifyMessagesController::class, 'search'])->name('search');
        Route::post('/shared', [ChatifyMessagesController::class, 'sharedPhotos'])->name('shared');
        
        // Borrar conversación (original)
        Route::post('/deleteConversation', [ChatifyMessagesController::class, 'deleteConversation'])
            ->name('conversation.delete');

        // Borrar mensaje (parche estable)
        Route::post('/deleteMessage', [\App\Http\Controllers\ChatifyPatchController::class, 'deleteMessage'])
            ->name('message.delete');

        // Ajustes y estado
        Route::post('/updateSettings', [ChatifyMessagesController::class, 'updateSettings'])->name('avatar.update');
        Route::post('/setActiveStatus', [ChatifyMessagesController::class, 'setActiveStatus'])->name('activeStatus.set');

        // Vistas por id (group / user)
        Route::get('/group/{id}', [ChatifyMessagesController::class, 'index'])->name('group');
        Route::get('/{id}',       [ChatifyMessagesController::class, 'index'])->name('user');
    });
