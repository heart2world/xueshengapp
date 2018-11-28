function request(option) {
    if(typeof(option) !== 'object') {
        console.warn("option is not a 'object'");
        return false;
    }
    if(typeof(option.loading) !== 'boolean') {
        var loading = layer.load(1);
    }

    option.url=option.url?option.url:$(".layui-form").attr("action");
    $.ajax({
        url: option.url || location.pathname,
        data: option.data || null,
        dataType: option.dataType || 'JSON',
        type: option.type || 'post',
        async: typeof(option.async) === 'boolean' ? option.async : true,
        success: option.success || function(res) {

            let url = res.url;
            url && (setTimeout(function() {
                location = url;
            }, 1000));
            var code = res.state==='success'?1:2;
            res.info && layer.msg(res.info, {icon:code});
            option.done && option.done(res);
        },
        complete: function() {
            if(typeof(option.loading) !== 'boolean') {
                layer.close(loading);
            }
            setTimeout(function() {
                var ret = option.reload || false;
                if(ret) {
                    ret = (typeof(ret === 'number')) ? ret : 0;
                    setTimeout(function() {
                        location.reload();
                    }, ret * 1000);
                }
            }, 10);
        },
        error: option.error || function(e) {
            layer.msg('error:' + e.statusText || e.statusMessage);
        }
    });
}
