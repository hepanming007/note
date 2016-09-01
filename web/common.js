var validate_form={
    is_chinese:function(str){
        var reg = /^([\u4e00-\u9fa5])+$/;
        return reg.test(str);
    },
    length:function(str){
        var len = 0;
        for (var i=0; i<str.length; i++) {
            if (str.charCodeAt(i)>127 || str.charCodeAt(i)==94) {
                len += 2;
            } else {
                len ++;
            }
        }
        return len;
    },
    empty:function(str){
      return str.length==0;
    },
    mobile:function(str){
        var reg = /^1[3|4|5|7|8][0-9]\d{8}$/;
        return reg.test(str)
    },
    card_number:function(str){
        var reg1 = /^\d{17}(\d|x)$/;
        var reg2 = /^\d{15}$/;
        return reg1.test(str) || reg2.test(str);
    }
};
