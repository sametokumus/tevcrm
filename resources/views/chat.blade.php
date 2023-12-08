@include('include.header')
<?php
$extra_js='
<script src="services/chat.js"></script>
';
?>

<!--app-content open-->
<div class="main-content app-content">
    <div class="side-app">

        <!-- CONTAINER -->
        <div class="main-container container-fluid">


            <div>
                <h1>Chat App</h1>

                <div class="container">
                    <div class="row">
                        <div id="chat-messages" style="width: 300px; border: 1px solid #ccc; padding: 10px; height: 200px; overflow-y: auto; margin-bottom: 30px;"></div>
                    </div>
                </div>

                <div class="container">
                    <div class="row">
                        <form id="chat-form">
                            <input type="text" id="message" placeholder="Type your message">
                            <button type="submit">Send</button>
                        </form>
                    </div>
                </div>


            </div>

        </div>
        <!-- CONTAINER END -->
    </div>
</div>
<!--app-content close-->

@include('include.footer')
