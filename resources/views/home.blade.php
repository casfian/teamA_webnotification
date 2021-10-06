@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <button onclick="startFCM()" class="btn btn-danger btn-flat">Need to Allow Notification</button>
            <br><br>

            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form action="{{ route('send.web-notification') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Message Title</label>
                            <input type="text" class="form-control" name="title">
                        </div>
                        <div class="form-group">
                            <label>Message Body</label>
                            <input type="text" class="form-control" name="body">
                        </div>
                        <button type="submit" class="btn btn-primary">Send Notification</button>
                    </form>



                </div>
            </div>
        </div>
    </div>
</div>

<!-- The core Firebase JS SDK is always required and must be listed first -->
<script src="https://www.gstatic.com/firebasejs/8.3.2/firebase.js"></script>

<script>
    var firebaseConfig = {
        apiKey: "AIzaSyCMM-yLnjJh-yfUkSb2FXknOVPJivoO1Z8",
        authDomain: "laravelnotifications1.firebaseapp.com",
        projectId: "laravelnotifications1",
        storageBucket: "laravelnotifications1.appspot.com",
        messagingSenderId: "211220431628",
        appId: "1:211220431628:web:58a98eb3abe320aed87f29"
    };
    firebase.initializeApp(firebaseConfig);
    const messaging = firebase.messaging();
    function startFCM() {
        messaging
            .requestPermission()
            .then(function () {
                return messaging.getToken()
            })
            .then(function (response) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '{{ route("store.token") }}',
                    type: 'POST',
                    data: {
                        token: response
                    },
                    dataType: 'JSON',
                    success: function (response) {
                        alert('Token stored.');
                    },
                    error: function (error) {
                        alert(error);
                    },
                });
            }).catch(function (error) {
                alert(error);
            });
    }
    messaging.onMessage(function (payload) {
        const title = payload.notification.title;
        const options = {
            body: payload.notification.body,
            icon: payload.notification.icon,
        };
        new Notification(title, options);
    });
</script>

@endsection
