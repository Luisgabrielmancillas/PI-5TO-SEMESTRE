{{-- -------------------- Saved Messages -------------------- --}}
@if($get == 'saved')
    <table class="messenger-list-item" data-contact="{{ Auth::user()->id }}">
        <tr data-action="0">
            {{-- Avatar side --}}
            <td>
                <div class="saved-messages avatar av-m">
                    <span class="far fa-bookmark"></span>
                </div>
            </td>
            {{-- center side --}}
            <td>
                <p data-id="{{ Auth::user()->id }}" data-type="user">
                    Saved Messages <span>You</span>
                </p>
                <span>Save messages secretly</span>
            </td>
        </tr>
    </table>
@endif


{{-- -------------------- Contact list -------------------- --}}
@if($get == 'users')
    @php
        $me = Auth::user();
        $isAdminView     = $me->role === 'admin';
        $isAdminContact  = isset($user) && (($user->role ?? null) === 'admin');
        // Regla: si soy admin veo a todos; si soy usuario, solo renderizo el contacto si es el admin
        $shouldRender    = $isAdminView || $isAdminContact;

        $hasMsg = !empty($lastMessage);
        if ($hasMsg) {
            $lastMessageBody = mb_convert_encoding($lastMessage->body, 'UTF-8', 'UTF-8');
            $lastMessageBody = strlen($lastMessageBody) > 30 ? mb_substr($lastMessageBody, 0, 30, 'UTF-8').'..' : $lastMessageBody;
        }
    @endphp

    @if($shouldRender)
        <table class="messenger-list-item {{ $isAdminContact && !$isAdminView ? 'pinned-admin' : '' }}" data-contact="{{ $user->id }}">
            <tr data-action="0">
                {{-- Avatar side --}}
                <td style="position: relative">
                    @if(!empty($user->active_status))
                        <span class="activeStatus"></span>
                    @endif
                    <div class="avatar av-m" style="background-image: url('{{ $user->avatar }}');"></div>
                </td>

                {{-- center side --}}
                <td>
                    <p data-id="{{ $user->id }}" data-type="user">
                        {{ strlen($user->name) > 12 ? trim(substr($user->name,0,12)).'..' : $user->name }}

                        {{-- Badge para distinguir al admin cuando navega un usuario --}}
                        @if($isAdminContact && !$isAdminView)
                            <span style="margin-left:6px;font-size:11px;background:#000000;color:#3f51b5;padding:2px 6px;border-radius:10px;">
                                Administrador
                            </span>
                        @endif

                        @if($hasMsg)
                            <span class="contact-item-time" data-time="{{ $lastMessage->created_at }}">
                                {{ $lastMessage->timeAgo }}
                            </span>
                        @endif
                    </p>

                    <span>
                        @if($hasMsg)
                            {!! $lastMessage->from_id == $me->id ? '<span class="lastMessageIndicator">You :</span>' : '' !!}

                            @if($lastMessage->attachment == null)
                                {!! $lastMessageBody !!}
                            @else
                                <span class="fas fa-file"></span> Attachment
                            @endif
                        @else
                            <em>Inicia la conversación</em>
                        @endif
                    </span>

                    {!! (!empty($unseenCounter) && $unseenCounter > 0) ? "<b>".$unseenCounter."</b>" : '' !!}
                </td>
            </tr>
        </table>
    @endif
@endif


{{-- -------------------- Search Item -------------------- --}}
@if($get == 'search_item')
    @php
        $me = Auth::user();
        $allowSearchItem = ($me->role === 'admin') || (($user->role ?? null) === 'admin');
    @endphp
    @if($allowSearchItem)
        <table class="messenger-list-item" data-contact="{{ $user->id }}">
            <tr data-action="0">
                {{-- Avatar side --}}
                <td>
                    <div class="avatar av-m" style="background-image: url('{{ $user->avatar }}');"></div>
                </td>
                {{-- center side --}}
                <td>
                    <p data-id="{{ $user->id }}" data-type="user">
                        {{ strlen($user->name) > 12 ? trim(substr($user->name,0,12)).'..' : $user->name }}
                    </p>
                </td>
            </tr>
        </table>
    @endif
@endif


{{-- -------------------- Shared photos Item -------------------- --}}
@if($get == 'sharedPhoto')
    <div class="shared-photo chat-image" style="background-image: url('{{ $image }}')"></div>
@endif