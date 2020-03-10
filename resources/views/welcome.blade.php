<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Free Reward</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <!-- jQuery Modal -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />
        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }

            button {
                font-size: 18px;
                border: 2px solid;
                border-radius: 100px;
                width: 150px;
                height: 150px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                    @else
                        <a href="{{ route('login') }}">Login</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif
                    @endauth
                </div>
            @endif

            <div class="content">
                <div class="title m-b-md">
                    <button id="big-red-button">ПРИЗ</button>
                </div>
                <div id="rewardModal" class="modal">
                    <p>Your reward is:</p>
                    <p id="rewardType"></p>
                    <p id="rewardAmount"><p>
                    <div>
                        <button id="acceptReward">Принять</button>
                        <button id="rejectReward">Отклонить</button>
                    </div>
                    <a href="#" rel="modal:close">Close</a>
                </div>
            </div>
        </div>
        <script>
            $.ajaxSetup({
                headers:{
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var reward = null;

            $("#acceptReward").click(function() {
                $.post("/reward/accept", {reward_id: reward.id})
                        .done(function (data) {
                            reward = null;
                            $("#rewardModal").hide();
                            alert('Your reward was saved');
                        });
            });

            $("#rejectReward").click(function() {
                $.post("/reward/reject", {reward_id: reward.id})
                        .done(function (data) {
                            reward = null;
                            $("#rewardModal").hide();
                            alert('You have rejected this reward');
                        });
            });

            $("#big-red-button").click(function() {
                $.post("/reward/claim", {})
                        .done(function (data) {
                            reward = data;
                            console.log('reward', reward);
                            $("#rewardType").html(data.type);
                            $("#rewardAmount").html(data.value);
                            $("#rewardModal").modal();
                        });
            });
        </script>
    </body>
</html>
