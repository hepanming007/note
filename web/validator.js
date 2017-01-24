   var validator  = {
        is_chinese : function (str) {
            var reg = /^([\u4e00-\u9fa5])+$/;
            return reg.test(str);
        },
        length : function (str) {
            var len = 0;
            for (var i = 0; i < str.length; i++) {
                if (str.charCodeAt(i) > 127 || str.charCodeAt(i) == 94) {
                    len += 2;
                } else {
                    len++;
                }
            }
            return len;
        },
        min_length:function(str,min){
            return this.length(str)>min?true:false;
        },
        max_lenght:function(str,max){
            return this.length(str)<max?true:false;
        },
        not_empty : function (str) {
            return str.length == 0?false:true;
        },
        mobile : function (str) {
            var reg = /^1[3|4|5|7|8][0-9]\d{8}$/;
            return reg.test(str)
        },
        card_number : function (str) {
            var reg1 = /^\d{17}(\d|x)$/;
            var reg2 = /^\d{15}$/;
            return reg1.test(str) || reg2.test(str);
        },
        email:function(str){
            return /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/.test( str );
        },
        url:function(str){
            return  /^(?:(?:(?:https?|ftp):)?\/\/)(?:\S+(?::\S*)?@)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})).?)(?::\d{2,5})?(?:[/?#]\S*)?$/i.test( str);
        },
        date: function(str) {
            return /\d+-\d+-\d+/.test (str);
        },
        // http://jqueryvalidation.org/dateISO-method/
        dateISO: function(str) {
            return  /^\d{4}[\/\-](0?[1-9]|1[012])[\/\-](0?[1-9]|[12][0-9]|3[01])$/.test( str );
        },
        // http://jqueryvalidation.org/number-method/
        number: function(str) {
            return  /^(?:-?\d+|-?\d{1,3}(?:,\d{3})+)?(?:\.\d+)?$/.test( str );
        },
        min:function(a,b){
            return a>b?true:false;
        },
        max:function(a,b){
            return a<b?true:false;
        },
        range:function(value,data){
           return (value>data[0]&&value<data[1])?true:false;
        },
        // http://jqueryvalidation.org/digits-method/
        digits: function(str) {
            return  /^\d+$/.test(str);
        },
        param:{},
        rules:{},
        setParam:function(param,rules){
            this.param = param;
            this.rules = rules;
            this.errorList = [];              

        },
        validate:function(){
            $.each(this.rules,function(i,data){
                validate_call = data[0];/*验证类型区分*/
                if(validate_call=='min_length'||validate_call=='min'||validate_call=='max'||validate_call=='range'){/*验证类型*/
                    if(!validator[validate_call](validator.param[i],data[2])){
                        validator.setError({msg:data[1]});
                    }
                }else{
                    if(!validator[validate_call](validator.param[i])){
                        validator.setError({msg:data[1]});
                    }
                }
            });
            if(this.hasErrors()){
                return false;
            }else{
                return true;
            }
        },
        errorList: [],
        setError:function(data){
            this.errorList.unshift(data);
        },
        hasErrors:function(){
            return this.errorList.length>0?true:false;
        },
        handleError:function(){
            $.each(this.errorList,function(i,data){
                 console.log(data);
            });
            this.errorList = [];
        }
    };
    var param = {
        a:'中文测试',
        b:'11111111',
        c:'',
        d:'12345681254',
        e:'350521199003032022',
        f:'hepm@ .com',
        g:'ht /www.独特.com',
        h:'2016-08-12',
        i:'dd ',
        j:123,
        k:100,
        l:9,
        m:100
    };
    var validate_rule = {
       a: ['is_chinese','输入的不是中文'],
       b: ['min_length','长度小于2',2],
       c: ['not_empty','不能为空'],
       d: ['mobile','手机号码错误'],
       e:['card_number','身份证号码错误'],
       f: ['email','邮箱错误'],
       g:['url','url错误'],
       h:['date','date错误'],
       i:['number','number错误'],
       j:['digits','digits错误'],
       k:['min','取值不能小于10',10],
       l:['max','取值不能大于10',10],
       m:['range','取值只能在10 20 之间',[10,20]]
    };
    validator.setParam(param,validate_rule);
    if(!validator.validate()){
       validator.handleError();
    }else{
        //提交表单
       console.log(validator.errorList);
    }
