<!DOCTYPE HTML>
<html lang="zh-CN">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=gbk" />
    <title>手机APP下载页面</title>
    <script type="text/javascript">

        function isWeiXin(){
            var ua = window.navigator.userAgent.toLowerCase();
            if(ua.match(/MicroMessenger/i) == 'micromessenger'){
                return true;
            }else{
                return false;
            }
        }
        //{ vs: "客户端版本号", ct: "客户端类型(android、iphone)" }
        var  androdUrl = 'http://wap.test.ohmate.com.cn/uploads/DoctorHelper_P1_1.0.7_release.apk';
        var app = { vs: "", ct: "" }, clientType = "", m = 0, u = window.navigator.userAgent, followState="", praiseState="";
        if(!!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/)){
            if((u.indexOf('iPhone') > -1) || (u.indexOf('iPod') > -1) || (u.indexOf('iPad') > -1)){
                clientType = "iphone";
                app.ct = clientType; //客户端类型
            }
        }else if(u.indexOf('Android') > -1 || u.indexOf('Linux') > -1){
            clientType = "android";
            app.ct = clientType; //客户端类型
        }
        if( isWeiXin() && app.ct=="android")
        {
            alert("由于微信浏览器限制，请点击右上角，选择“在浏览器中打开”");
            window.location.href = androdUrl;
        }
        if( !isWeiXin() && app.ct=="android")
        {
            window.location.href = androdUrl;
        }
        if( isWeiXin() && app.ct=="iphone")
        {
            alert("IOS需要提供uuid,由于微信浏览器限制，请点击右上角，选择“使用Safari打开”");
            //window.location.href = 'https://appsto.re/cn/vGPwbb.i';
        }
        if( !isWeiXin() && app.ct=="iphone")
        {
            //window.location.href = 'http://www.chengliwang.com/package/木奇灵AR.apk';
            alert("IOS需要提供uuid,由于微信浏览器限制，请点击右上角，选择“使用Safari打开”");
        }

    </script>
</head>
<body>

</body>
</html>
