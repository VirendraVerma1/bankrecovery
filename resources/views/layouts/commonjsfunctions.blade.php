<script>
    window.oncontextmenu = null;
    
    //notification mechanism ------------------------------------------------------------------------------

    //get msg from the session if exist(only for success, warning, danger msg type)
    var showNotification=@if(session('success')||session('warning')||session('danger')) true @else false @endif ;
    var msgType=@if(session('success')) "success" @elseif(session('warning')) "warning" @elseif(session('danger')) "danger" @else "" @endif ;
    var msg=@if(session('success')) "{{session('success')}}" @elseif(session('warning')) "{{session('warning')}}" @elseif(session('danger')) "{{session('danger')}}" @else "" @endif ;

    //initialize variables for the notification
    var notificationbox=document.getElementById("mynotificationbox");
    var notificationimage=document.getElementById("mynotificationimage");
    var notificationtype=document.getElementById("mynotificationtype");
    var notificationmsg=document.getElementById("mynotificationmsg");
    
    //for checking purpose
    var notificationBoxVisible=false;

    $(document).ready(function () {
        showCustomNotification();//from here we will get the loaded msg from the controller
    });

    function customLoadAndShowMSG(MSGType,MSG)//this function helps in showing msg in realtime
    {
        showNotification=true;
        msgType=MSGType;
        msg=MSG;
        showCustomNotification();
    }

    function showCustomNotification()
    {
        if(showNotification)//have msg
        {
            
            //changing image according to the status
            var img_path="";
            if(msgType=="success")
                img_path="{{asset('images/myicons/success.jpg')}}";
            else if(msgType=="warning")
                img_path="{{asset('images/myicons/warning.jpg')}}";
            else if(msgType=="danger")
                img_path="{{asset('images/myicons/danger.jpg')}}";
            
            //alert("tale2="+img_path);
            
            $("#mynotificationimage").attr('src',img_path);
            $("#mynotificationtype").text(msgType);
            $("#mynotificationmsg").text(msg);
            $("#mynotificationbox").css('opacity', '1');
            //notificationbox.style.opacity=1;//it will show the notification
            if(notificationBoxVisible==false)
            {
                notificationBoxVisible=true;
                setTimeout(closenotification, 5000);//after 5 sec notification will automatically hide
            }
        
        }
        else  //dont have any msg
        {
            if(notificationBoxVisible==false)
            {
                //$("#mynotificationbox").css('opacity', '0');//it will hide the notification
            }
        }
    }

    function closenotification()
    {
        notificationBoxVisible=false;
        $("#mynotificationbox").css('opacity', '0');
        //document.getElementById("mynotificationbox").style.opacity=0;
    }


</script>

