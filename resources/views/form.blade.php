<form action="" method="POST">
    {{--<label for="phone_ip">IP address of phone <br>--}}
        {{--<input type="text" name="phone_ip" id="phone_ip" />--}}
    {{--</label>--}}
    {{--<br>--}}
    {{--<br>--}}
    <label for="number">Phone number to call <br>
        <input type="text" name="number" id="number" />
    </label>
    <br>
    <input type="submit" />
</form>

<label for="phone_ip">IP address of phone <br>
    <input type="text" name="phone_ip" id="phone_ip" />
</label>
<br />

<button onclick="call203();">203</button>

<script src="//code.jquery.com/jquery-2.2.3.min.js"></script>

<script>

    function call203(){
        $.ajax({
            type: "POST",
            url: "http://" + $('#phone_ip').val() + "/push",
            headers: {
                "Authorization", "Digest polycom:456"
            },
            contentType: "application/x-com-polycom-spipx",
            data: '<PolycomIPPhone><Data priority="Critical">tel://203</Data></PolycomIPPhone>',
            success: function (data) {
                console.log(data);
            }
        });
    }

</script>