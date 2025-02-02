@extends('layout')
@section('style')
<style>
    .avatar-50 {
        height: 50px;
        width: 50px;
        border-radius: 50%; /* Make the image circular */
        object-fit: cover;
    }
    .avatar-35 {
        height: 35px;
        width: 35px;
        border-radius: 50%; /* Make the image circular */
        object-fit: cover;
    }
</style>
@endsection

@section('content')

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body chat-page p-0">
                <div class="chat-data-block">
                    <div class="row">
                        <div class="col-lg-3 chat-data-left scroller">
                            <div class="chat-search pt-3 ps-3">
                                <div class="d-flex align-items-center">
                                    <div class="chat-profile me-3">
                                        @if($profilePicture && $profilePicture->file_path)
                                            <img src="{{ Storage::url($profilePicture->file_path) }}" alt="profile-img" class="avatar-60 img-fluid rounded-circle" style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover;"/>
                                        @else
                                            <img src="{{ asset('/images/template/user/Noprofile.jpg') }}" alt="profile-img" class="avatar-60 img-fluid rounded-circle" style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover;" />
                                        @endif
                                    </div>
                                    <div class="chat-caption">
                                        <h5 class="mb-0">{{ auth()->user()->name }}</h5>
                                    </div>
                                    <button type="submit" class="close-btn-res p-3"><i class="ri-close-fill"></i></button>
                                </div>
                            </div>
                            <div class="chat-sidebar-channel scroller mt-4 ps-3">
                                <h5 class="mt-3">Direct Message</h5>
                                <ul id="chat-list" class="iq-chat-ui nav flex-column nav-pills">
                                    @forelse($messages as $key => $message)
                                        <li class="chat-item">
                                            <a data-bs-toggle="pill" href="#chatbox{{ $key + 1 }}">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar me-2">
                                                        <img src="{{ $message['friend_profile_picture'] }}" alt="Friend Image" class="avatar-50 rounded-circle" onerror="this.onerror=null; this.src='{{ asset('images/template/user/Noprofile.jpg') }}'">
                                                        <span class="avatar-status"><i class="ri-checkbox-blank-circle-fill text-dark"></i></span>
                                                    </div>
                                                    <div class="chat-sidebar-name">
                                                        <h6 class="mb-0">{{ $message['friend_name'] }}</h6>
                                                        <span>{{ ((end($message['conversations'])['user_id'] != auth()->id()) ? '' : 'You:').''.end($message['conversations'])['message'] }}</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                    @empty
                                        <p>No messages found.</p>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-9 chat-data p-0 chat-data-right">
                            <div class="tab-content">
                                <div class="tab-pane fade active show" id="default-block" role="tabpanel">
                                    <div class="chat-start">
                                        <span class="iq-start-icon text-primary"><i class="ri-message-3-line"></i></span>
                                        <button id="chat-start" class="btn bg-white mt-3">Start Conversation!</button>
                                    </div>
                                </div>
                                @foreach($messages as $key => $message)
                                    <div class="tab-pane fade" id="chatbox{{ $key + 1 }}" role="tabpanel">
                                        <div class="chat-head">
                                            <header class="d-flex justify-content-between align-items-center bg-white pt-3 pe-3 pb-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="sidebar-toggle">
                                                        <i class="ri-menu-3-line"></i>
                                                    </div>
                                                    <div class="avatar chat-user-profile m-0 me-3">
                                                        <img src="{{ $message['friend_profile_picture'] }}" alt="Friend Image" class="avatar-50 rounded-circle">
                                                        <span class="avatar-status"><i class="ri-checkbox-blank-circle-fill text-success"></i></span>
                                                    </div>
                                                    <h5 class="mb-0">{{ $message['friend_name'] }}</h5>
                                                </div>
                                            </header>
                                        </div>
                                        <div class="chat-content scroller chatContent{{ $key + 1 }}">
                                            @foreach($message['conversations'] as $chatText)
                                                <div class="chat {{ ($chatText['user_id'] != auth()->id()) ? 'chat-left' : 'd-flex other-user'}}">
                                                    <div class="chat-user">
                                                        <a class="avatar m-0">
                                                            <img src="{{ ($chatText['user_id'] != auth()->id()) ? $message['friend_profile_picture'] : Storage::url($profilePicture->file_path) }}" alt="avatar" class="avatar-35 rounded-circle">
                                                        </a>
                                                        {{-- <span class="chat-time mt-1">{{ date('h:i a', strtotime($chatText['converted_date'])) }}</span> --}}
                                                        <span class="chat-time mt-1">
                                                            {{ \Carbon\Carbon::parse($chatText['converted_date'])->format('Y-m-d H:i:s') }}
                                                        </span>

                                                    </div>
                                                    <div class="chat-detail">
                                                        <div class="chat-message">
                                                            <p>{{ $chatText['message'] }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="chat-footer p-3 bg-white">
                                            <form class="d-flex align-items-center" action="#">
                                                <div class="chat-attagement d-flex">
                                                    <a href="#"><i class="far fa-smile pe-3" aria-hidden="true"></i></a>
                                                    <a href="#"><i class="fa fa-paperclip pe-3" aria-hidden="true"></i></a>
                                                </div>
                                                <input type="text" class="chatMessage{{ $key + 1 }} form-control me-3" placeholder="Type your message">
                                                <button type="submit" data-chat-box="chatContent{{ $key + 1 }}" data-message-class="chatMessage{{ $key + 1 }}" data-friend-id="{{ $message['friend_id'] }}" class="sendChat btn btn-primary d-flex align-items-center px-2">
                                                    <i class="far fa-paper-plane" aria-hidden="true"></i>
                                                    <span class="d-none d-lg-block ms-1">Send</span>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
    $(document).ready(function(){
        $('#chat-search').on('keyup', function(){
            var value = $(this).val().toLowerCase();
            $('#chat-list .chat-item').filter(function(){
                $(this).toggle($(this).find('.chat-sidebar-name h6').text().toLowerCase().indexOf(value) > -1);
            });
        });

        $('.sendChat').on('click', function(e) {
            e.preventDefault();

            var $this = $(this);
            var messageClass = $this.data('message-class');
            var chatBox = $this.data('chat-box');
            var textarea = $('.' + messageClass);
            var chatMessage = textarea.val();
            var friendId = $this.data('friend-id');
            var userImage = '{{ Storage::url($profilePicture->file_path) }}'; // Replace with the actual user image path
            var friendProfileImage = '{{ $message['friend_profile_picture'] }}';

            if (textarea.val().trim() === '') {
                return; // Do nothing if the textarea is empty
            }

            $.ajax({
                url: "{{ route('chat.sendMessage') }}",
                type: "POST",
                data: {
                    message: chatMessage,
                    friend_id: friendId,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    toastr.success(response.message);

                    var chatHtml = `
                        <div class="chat d-flex other-user">
                            <div class="chat-user">
                                <a class="avatar m-0" >
                                    <img src="${userImage}" alt="avatar" class="avatar-35 rounded-circle" >
                                </a>
                                <span class="chat-time mt-1">${new Date(response.data.created_at).toLocaleTimeString([], {hour: '2-digit', minute: '2-digit'})}</span>
                            </div>
                            <div class="chat-detail">
                                <div class="chat-message">
                                    <p>${response.data.message}</p>
                                </div>
                            </div>
                        </div>
                    `;

                    $('.' + chatBox).append(chatHtml);
                    textarea.val(''); // Clear the textarea
                },
                error: function(xhr, status, error) {
                    toastr.error('An error occurred while sending the message.');
                }
            });
        });
    });
</script>
@endsection
