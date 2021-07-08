<script>
    // Send Message form admin In support Chat 
    $('.send_msg').on('click',function(e){
        var send_msg = $(this);
        var resuesturl = $(this).attr("data-url");
        var URL = window.location.origin+resuesturl;
        var ticketId = $(this).attr("data-ticketId");
        var textMsg = $('.text_msg').val();
        var textMsg = $.trim(textMsg);
        if(textMsg == ""){
            $('.error').show();
            setTimeout(() => {
                $('.error').hide();
            }, 3000);
            $('.text_msg').val("");
            return false;
        }
        $.ajax({
            type: "post",
            url: URL,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType:'json',
            data: {
                'ticket_id':ticketId,
                'comment': textMsg
            },
            success: function (data) {
                if(data.success){
                    let allData = data.data;
                    $('.text_msg').val("");
                    var html = `<div class="outgoing_msg"> <div class="sent_msg"> <p>${allData.comment}</p> <span class="time_date text-right">${moment(allData.created_at).format("hh:mm A | MMM DD")}</span> </div> </div>`;
                    $('#msg_history').append(html);
                    var objDiv = document.getElementById("msg_history");
                    objDiv.scrollTop = objDiv.scrollHeight;
                }else{
                    $('.error').text(data.message);
                    $('.error').show();
                    setTimeout(() => {
                        $('.error').hide();
                    }, 3000);
                }
            }         
        });
    });
    // End Send Message form admin In support Chat
    $(document).ready(function() {
        $(".content-text .fancybox-thumb").fancybox({
            prevEffect	: 'none',
            nextEffect	: 'none',
            helpers	: {
                thumbs	: {
                    width	: 80,
                    height	: 80
                }
            }
        });
        $(".image-with-comment .fancybox-thumb").fancybox({
            prevEffect	: 'none',
            nextEffect	: 'none',
            helpers	: {
                thumbs	: {
                    width	: 80,
                    height	: 80
                }
            }
        });

        $("a[rel=fancybox-thumb-multiple]").fancybox({
            'transitionIn'      : 'none',
            'transitionOut'     : 'none',
            'titlePosition'     : 'over',
            'cyclic'            : true,
            'titleFormat'       : function(title, currentArray, currentIndex, currentOpts) {
                return '<span id="fancybox-title-over">Image ' +  (currentIndex + 1) + ' / ' + currentArray.length + ' ' + title + '</span>';
            }
        });
        
    });
</script>