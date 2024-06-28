@extends('layout')

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
                               {{-- <img src="{{ $userImage }}" alt="chat-user" class="avatar-60 "> --}}
                               @if($profilePicture && $profilePicture->file_path)
                               <img src="{{ Storage::url($profilePicture->file_path) }}" alt="profile-img" class="avatar-60 img-fluid rounded-circle" style="width: 60px; height: 55px; border-radius: 50%; object-fit: cover;"/>
                               @else
                               <img src="{{ asset('/images/template/user/11.png') }}" alt="profile-img" class="avatar-60 img-fluid rounded-circle" style="width: 60px; height: 55px; border-radius: 50%; object-fit: cover;" />
                               @endif
                            </div>
                            <div class="chat-caption">
                               <h5 class="mb-0">{{ auth()->user()->name }}</h5>
                            </div>
                            <button type="submit" class="close-btn-res p-3"><i class="ri-close-fill"></i></button>
                         </div>
                         {{-- <div id="user-detail-popup" class="scroller">
                            <div class="user-profile">
                               <button type="submit" class="close-popup p-3"><i class="ri-close-fill"></i></button>
                               <div class="user text-center mb-4">
                                  <a class="avatar m-0">
                                   @if($profilePicture && $profilePicture->file_path)
                               <img src="{{ Storage::url($profilePicture->file_path) }}" alt="profile-img" class="avatar-60 img-fluid rounded-circle" style="width: 60px; height: 55px; border-radius: 50%; object-fit: cover;"/>
                               @else
                               <img src="{{ asset('/images/template/user/11.png') }}" alt="profile-img" class="avatar-60 img-fluid rounded-circle" style="width: 60px; height: 55px; border-radius: 50%; object-fit: cover;" />
                               @endif
                                  </a>
                                  <div class="user-name mt-4">
                                     <h4 class="text-center">{{ auth()->user()->name }}</h4>
                                  </div>
                               </div>
                               <hr>
                               <div class="user-detail text-left mt-4 ps-4 pe-4">
                                  <h5 class="mt-4 mb-4">About</h5>
                                  <p>It is long established fact that a reader will be distracted bt the reddable.</p>
                                  <h5 class="mt-3 mb-3">Status</h5>
                                  <ul class="user-status p-0">
                                     <li class="mb-1"><i class="ri-checkbox-blank-circle-fill text-success pe-1"></i><span>Online</span></li>
                                     <li class="mb-1"><i class="ri-checkbox-blank-circle-fill text-warning pe-1"></i><span>Away</span></li>
                                     <li class="mb-1"><i class="ri-checkbox-blank-circle-fill text-danger pe-1"></i><span>Do Not Disturb</span></li>
                                     <li class="mb-1"><i class="ri-checkbox-blank-circle-fill text-light pe-1"></i><span>Offline</span></li>
                                  </ul>
                               </div>
                            </div>
                         </div> --}}
                         <div class="chat-searchbar mt-4">
                            <div class="form-group chat-search-data m-0">
                               <input type="text" class="form-control round" id="chat-search" placeholder="Search">
                               <i class="ri-search-line"></i>
                            </div>
                         </div>
                      </div>
                      <div class="chat-sidebar-channel scroller mt-4 ps-3">
                         <h5 class="mt-3">Direct Message</h5>
                         <ul id="chat-list" class="iq-chat-ui nav flex-column nav-pills">
                           <?php $count = 0; ?>
                           @forelse($messages as $key => $message)
                           <?php $count++; ?>
                              <li class="chat-item">
                                 <a  data-bs-toggle="pill" href="#chatbox{{$count+1}}">
                                    <div class="d-flex align-items-center">
                                       <div class="avatar me-2">
                                          <img src="{{ $message['friend_image'] }}" alt="chatuserimage" class="avatar-50 ">
                                          <span class="avatar-status"><i class="ri-checkbox-blank-circle-fill text-dark"></i></span>
                                       </div>
                                       <div class="chat-sidebar-name">
                                          <h6 class="mb-0">{{ $message['friend_name'] }}</h6>
                                          <span>
                                             {{ ((end($message['conversations'])['user_id'] != auth()->id()) ? '' : 'You:').''.end($message['conversations'])['message'] }}
                                          </span>
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
                               <button id="chat-start" class="btn bg-white mt-3">Start
                               Conversation!</button>
                            </div>
                         </div>
                         <?php $count = 0; ?>
                         @foreach($messages as $message)
                         <?php $count++; ?>
                           <div class="tab-pane fade" id="chatbox{{$count+1}}" role="tabpanel">
                              <div class="chat-head">
                                 <header class="d-flex justify-content-between align-items-center bg-white pt-3 pe-3 pb-3">
                                    <div class="d-flex align-items-center">
                                       <div class="sidebar-toggle">
                                          <i class="ri-menu-3-line"></i>
                                       </div>
                                       <div class="avatar chat-user-profile m-0 me-3">
                                          <img src="{{ $message['friend_image'] }}" alt="avatar" class="avatar-50 ">
                                          <span class="avatar-status"><i class="ri-checkbox-blank-circle-fill text-success"></i></span>
                                       </div>
                                       <h5 class="mb-0">{{$message['friend_name']}}</h5>
                                    </div>
                                    <div class="chat-user-detail-popup scroller">
                                       <div class="user-profile">
                                          <button type="submit" class="close-popup p-3"><i class="ri-close-fill"></i></button>
                                          <div class="user mb-4  text-center">
                                             <a class="avatar m-0">
                                             <img src="{{ $message['friend_image'] }}" alt="avatar">
                                             </a>
                                             <div class="user-name mt-4">
                                                <h4>{{$message['friend_name']}}</h4>
                                             </div>
                                          </div>
                                          <hr>
                                          <div class="chatuser-detail text-left mt-4">
                                             <div class="row">
                                                <div class="col-6 col-md-6 title">Name:</div>
                                                <div class="col-6 col-md-6 text-right">{{$message['friend_name']}}</div>
                                             </div>
                                             <hr>
                                             <div class="row">
                                                <div class="col-6 col-md-6 title">Tel:</div>
                                                <div class="col-6 col-md-6 text-right">XXXXXXXXXX</div>
                                             </div>
                                             <hr>
                                             <div class="row">
                                                <div class="col-6 col-md-6 title">Date Of Birth:</div>
                                                <div class="col-6 col-md-6 text-right">XXXX-XX-XX</div>
                                             </div>
                                             <hr>
                                             <div class="row">
                                                <div class="col-6 col-md-6 title">Gender:</div>
                                                <div class="col-6 col-md-6 text-right">X</div>
                                             </div>
                                             <hr>
                                             <div class="row">
                                                <div class="col-6 col-md-6 title">Language:</div>
                                                <div class="col-6 col-md-6 text-right">English</div>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="chat-header-icons d-flex">
                                       <a href="#" class="chat-icon-phone bg-soft-primary">
                                       <i class="ri-phone-line"></i>
                                       </a>
                                       <a href="#" class="chat-icon-video bg-soft-primary">
                                       <i class="ri-vidicon-line"></i>
                                       </a>
                                       <a href="#" class="chat-icon-delete bg-soft-primary">
                                       <i class="ri-delete-bin-line"></i>
                                       </a>
                                       <span class="dropdown bg-soft-primary">
                                          <i class="ri-more-2-line cursor-pointer dropdown-toggle nav-hide-arrow cursor-pointer pe-0" id="dropdownMenuButton02" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></i>
                                          <span class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton02">
                                             <a class="dropdown-item" href="#"><i class="ri-pushpin-2-line me-1 h5"></i>Pin to top</a>
                                             <a class="dropdown-item" href="#"><i class="ri-delete-bin-6-line me-1 h5"></i>Delete chat</a>
                                             <a class="dropdown-item" href="#"><i class="ri-time-line me-1 h5"></i>Block</a>
                                          </span>
                                       </span>
                                    </div>
                                 </header>
                              </div>
                              <div class="chat-content scroller chatContent{{$count}}">
                                 @foreach($message['conversations'] as $chatText)
                                    <div class="chat {{ ($chatText['user_id'] != auth()->id()) ? 'chat-left' : 'd-flex other-user'}}">
                                       <div class="chat-user">
                                          <a class="avatar m-0">
                                          {{-- <img src="{{ ($chatText['user_id'] != auth()->id()) ? $message['friend_image'] : $userImage }}" alt="avatar" class="avatar-35 ">
                                           --}}
                                           @if($profilePicture && $profilePicture->file_path)
                                           <img src="{{ Storage::url($profilePicture->file_path) }}" alt="profile-img" class="avatar-35 "/>
                                           @else
                                           <img src="{{ asset('/images/template/user/11.png') }}" alt="profile-img" class="avatar-35" />
                                           @endif
                                          </a>
                                          <span class="chat-time mt-1">{{date('h:i a',strtotime($chatText['converted_date']))}}</span>
                                       </div>
                                       <div class="chat-detail">
                                          <div class="chat-message">
                                             <p>{{$chatText['message']}}</p>
                                          </div>
                                       </div>
                                    </div>
                                 @endforeach
                              </div>
                              <div class="chat-footer p-3 bg-white">
                                 <form class="d-flex align-items-center"  action="#">
                                    <div class="chat-attagement d-flex">
                                       <a href="#"><i class="far fa-smile pe-3" aria-hidden="true"></i></a>
                                       <a href="#"><i class="fa fa-paperclip pe-3" aria-hidden="true"></i></a>
                                    </div>
                                    <input type="text" class="chatMessage{{$count}} form-control me-3" placeholder="Type your message">
                                    <button type="submit" data-chat-box="chatContent{{$count}}" data-message-class="chatMessage{{$count}}" data-friend-id="{{$message['friend_id']}}" class="sendChat btn btn-primary d-flex align-items-center px-2">
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
            var textarea = $('.'+messageClass);
            var chatMessage = textarea.val();
            var friendId = $this.data('friend-id');
            var userImage = '{{$userImage}}'; // replace with actual user image path
            // console.log(userImage);

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
                                 <a class="avatar m-0">
                                    <img src="${userImage}" alt="avatar" class="avatar-35">
                                 </a>
                                 <span class="chat-time mt-1"></span>
                              </div>
                              <div class="chat-detail">
                                 <div class="chat-message">
                                    <p>${response.data.message}</p>
                                 </div>
                              </div>
                        </div>
                     `;

                     $('.'+chatBox).append(chatHtml);
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
