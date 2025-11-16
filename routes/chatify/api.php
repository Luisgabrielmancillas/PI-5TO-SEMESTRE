<?php

use Illuminate\Support\Facades\Route;
use Chatify\Http\Controllers\MessagesController as ChatifyMessagesController;

Route::prefix(config('chatify.api_routes.prefix', 'chatify/api'))
    ->middleware(['api']) // ->middleware(['api','restrict.chatify']) si lo quieres también aquí
    ->group(function () {
        Route::post('/chat/auth', [ChatifyMessagesController::class, 'pusherAuth'])->name('api.pusher.auth');

        Route::post('/idInfo',       [ChatifyMessagesController::class, 'idFetchData'])->name('api.idInfo');
        Route::post('/sendMessage',  [ChatifyMessagesController::class, 'send'])->name('api.send.message');
        Route::post('/fetchMessages',[ChatifyMessagesController::class, 'fetch'])->name('api.fetch.messages');

        Route::get ('/download/{fileName}', [ChatifyMessagesController::class, 'download'])
            ->name('api.'.config('chatify.attachments.download_route_name'));

        Route::post('/makeSeen', [ChatifyMessagesController::class, 'seen'])->name('api.messages.seen');
        Route::get ('/getContacts', [ChatifyMessagesController::class, 'getContacts'])->name('api.contacts.get');

        Route::post('/star',      [ChatifyMessagesController::class, 'favorite'])->name('api.star');
        Route::post('/favorites', [ChatifyMessagesController::class, 'getFavorites'])->name('api.favorites');

        Route::get ('/search', [ChatifyMessagesController::class, 'search'])->name('api.search');
        Route::post('/shared', [ChatifyMessagesController::class, 'sharedPhotos'])->name('api.shared');

        Route::post('/deleteConversation', [ChatifyMessagesController::class, 'deleteConversation'])->name('api.conversation.delete');
        Route::post('/updateSettings',     [ChatifyMessagesController::class, 'updateSettings'])->name('api.avatar.update');
        Route::post('/setActiveStatus',    [ChatifyMessagesController::class, 'setActiveStatus'])->name('api.activeStatus.set');
    });
