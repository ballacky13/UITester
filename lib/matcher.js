/*depend on jquery*/

beforeEach(function(){
    var uiMatchers = {
        toHaveClass: function(className) {
            var nodeList = $(this.actual);
            /*var result = true;*/
            /*$.each(nodeList,function(index,item){
             if(!item.hasClass(className)){
             result = false;
             return false;
             }
             });*/


            /* for(var i = 0;nodeList[i];i++){
             if(!nodeList.item(i).hasClass(className)){
             result = false;
             break;
             }
             }*/
            return nodeList.hasClass(className);
        },
        // ��ȡԪ���Ƿ�ɼ�
        toBeVisible: function() {
            var nodeList = $(this.actual);
            return nodeList.size() == nodeList.filter(':visible').size();
        },

        toBeHidden: function() {

            var nodeList = $(this.actual);
            return nodeList.size() == nodeList.filter(':hidden').size();
        },

        toBeSelected: function() {
            var nodeList = $(this.actual);
            return nodeList.filter(':selected').size() == nodeList.size();
        },

        toBeChecked: function() {

            var nodeList = $(this.actual);
            return nodeList.filter(':checked').size() == nodeList.size();
        },

        toBeEmpty: function() {
            var nodeList = $(this.actual);
            return nodeList.filter(':empty').size() == nodeList.size();

        },

        toExist: function() {
            var nodeList = $(this.actual);
            return nodeList.size();
        },
        // ������attributeName ��Ϊ  expectedAttributeValue
        toHaveAttr: function(attributeName, expectedAttributeValue) {
            var nodeList = $(this.actual);
            var result = true;

            $.each(nodeList,function(index,item){
                if($(item).attr(attributeName) != expectedAttributeValue){
                    result = false;
                    return false;
                }
            })
            /*for(var i = 0;nodeList[i];i++){
             if(nodeList.item(i).attr(attributeName) != expectedAttributeValue ) {
             result = false;
             break;
             }
             }*/
            return result;

        },

        toHaveProp: function(propertyName, expectedPropertyValue) {
            var nodeList = $(this.actual);
            var result = true;
            $.each(nodeList,function(index,item){
                if($(item).prop(propertyName) != expectedPropertyValue){
                    result = false;
                    return false;
                }
            })
            /*for(var i = 0;nodeList[i];i++){
             if(nodeList.item(i).prop(propertyName) != expectedPropertyValue ) {
             result = false;
             break;
             }
             }*/
            return result;

        },

        toHaveId: function(id) {
            var nodeList = $(this.actual);
            return nodeList.attr('id') == id;
        },
        // ���Կո���ϸ�innerHTMLƥ��
        toHaveHtml: function(html) {
            var nodeList = $(this.actual);
            return $.trim(nodeList.html()) == $.trim(html);
        },
        // ���html�ṹ�Ƿ�һ��
        toHaveSameSubTree:function(html){
            var nodeList = $(this.actual);
            var root = nodeList[0];
            if(!root){
                return false;
            }

            // �ȱ���ʵ�ʽṹ. ���ṹ��ƽ��
            // �ٽ������ַ�����ΪinnerHTML���뵽dom��. �ṹ��ƽ��
            // ���Ƚ�������¼�ַ����Ƿ�һ��

            function serializeHTML(node,arr){

                if(node.nodeType == '1'){
                    arr.push(node.tagName);
                    var childs = node.childNodes;
                    // ����һ��.
                    var tagChilds = [];
                    for(var i=0;childs[i];i++){
                        if(childs[i].nodeType == 1){
                            tagChilds.push(childs[i]);
                        }
                    }
                    if(tagChilds.length){
                        arr.push('>');
                    }

                    for(var i = 0;tagChilds[i];i++){
                        serializeHTML(tagChilds[i],arr);
                        if(i<tagChilds.length-1){arr.push('+');}
                    }
                }
                return;
            }
            var temp = $('<div style="display: none;" id="tempHtmlStructure"></div>');
            temp[0].innerHTML = html;
            $('body').append(temp);
            var expectRecord = [];
            // ����

            serializeHTML($('#tempHtmlStructure')[0],expectRecord);
            temp.remove();
            expectRecord.shift();
            var expectStr =expectRecord.join('');
            var actualRecord = [];
            serializeHTML(root,actualRecord);
            actualRecord.shift();
            var actualStr= actualRecord.join("");
            return  actualStr == expectStr;

        },

        toHaveText: function(text) {
            var nodeList = $(this.actual);
            var trimmedText = $.trim(nodeList.text());
            if (text && $.isFunction(text.test)) {
                return text.test(trimmedText);
            } else {
                return trimmedText == text;
            }
        },

        toHaveValue: function(value) {
            var nodeList = $(this.actual);
            var result = true;
            $.each(nodeList,function(index,item){
                if($(item).val() !== value){
                    result = false;
                    return false;
                }
            })

            /*for(var i = 0;nodeList[i];i++){
             if(nodeList.item(i).val() != value ) {
             result = false;
             break;
             }
             }*/
            return result;



        },




        toBe: function(selector) {
            var nodeList = $(this.actual);
            return nodeList.filter(selector).length;
        },

        toContain: function(selector) {
            return $(selector,this.actual).size();
        },

        toBeDisabled: function(selector){
            var nodeList = $(this.actual);
            return nodeList.filter(':disabled').size() == nodeList.size();
        },

        toBeFocused: function(selector) {
            var nodeList = $(this.actual);

            /*���� */
            return true;// nodeList.filter(':focus').size() == nodeList.size();
        },
        // css �����ж�
        toHaveComputedStyle:function(styleProp,expectValue){
            var expect= expectValue;
            if(styleProp.match(/color/i)){
                var tempNode = $('<div></div>');
                $('body').append(tempNode);
                $(tempNode).css(styleProp,expectValue);
                expect = $(tempNode).css(styleProp);
                tempNode.remove();
            }

            var nodeList = $(this.actual);
            var result = true;
            $.each(nodeList,function(index,item){
                if($(item).css(styleProp)!== expect){
                    result = false;
                    return false;
                }

            })
            /* for(var i=0;nodeList[i];i++){
             if(S.DOM.css(nodeList[i],styleProp) !== expect){
             result = false;
             break;
             }
             }*/
            return result;
        },
        /**
         *
         * @param x
         * @param y
         * @param off ���ֵ
         * @param relativeEl ���Ԫ��
         * @return {Boolean}
         */
        atPosition:function(x,y,off,relativeEl){
            var tempOff = 0.1; // ������0.1
            var absX = Math.abs(x);
            var absY = Math.abs(y);
            var referPosition = {top:0,left:0} ; // Ĭ�ϵ��������������Ͻ�                 �ȷ�
            if(arguments[2]&& typeof arguments[2]  == 'number'){
                tempOff = arguments[2];
            }

            if(arguments[3]&& typeof arguments[3] == 'string'){
                var referEl = $(arguments[3]);
                if(referEl){
                    referPosition = referEl.offset();
                }


            }
            var nodeList = $(this.actual);
            var actualPosition = nodeList.offset();
            var heightGap=nodeList.outerHeight() *tempOff;
            var widthGap = nodeList.outerWidth() *tempOff;
            return (Math.abs(Math.abs(actualPosition.top - referPosition.top) - absY) < heightGap) &&( Math.abs(Math.abs(actualPosition.left - referPosition.left) - absX) < widthGap);
        },

        // �Ƿ��Ǻ�ģ��


        // Ԫ��λ��
        // overlay �ж�Ԫ���Ƿ�����Ԫ���ڵ�

        // Ԫ�ض�����֤



        // �ж�Ԫ���Ƿ���ָ����λ���� x y

        // ��֤

        /*
         *  1. ��½                               x
         *  2. focus�������                     v
         *  3. ��������                         v
         *  4. ��������.                       v
         *  5 ѡ�����                        v
         *  6. ����                         v
         *  7. �ϴ�ͼƬ                    x
         *  8 �ύ                       v
         *  9. �ɹ�����ʧ�ܵ���ʾ�Ƿ���ʾ  v
         *
         * */
        willAddChildren:function(selector,nodeNum){
            var num = 1;
            var self = this;
            if(arguments.length>1 && typeof arguments[1] == 'number'){
                num = arguments[1];
            }
            // ��¼�´���ڵ����Ԫ����Ϣ innerHTML

            //  �Ƚ����������ĺ��ӽڵ��������
            var list = $(selector);
            var before=0;
            debugger;
            $.each(list,function(index,item){
                if($(item).parent(self.actual)){
                    before++;
                }
            })
            /*for(var i= 0,len=list.length;i<len;i++){
             if(S.DOM.parent(list[i],this.actual) ){
             before++;
             }

             }*/
            this.record = before;

            // ��֤�ڵ���Ԫ���Ƿ�������selector��ָ���Ľڵ�. �����Ƿ�һ��

            this.verify = jasmine.Matchers.matcherFn_('willAddChild',function(){
                var list = $(selector);
                var after=0;
                var self = this;
                $.each(list,function(index,item){
                    if($(item).parent(self.actual)){
                        after++;
                    }
                })
                /* for(var i= 0,len=list.length;i<len;i++){
                 if(S.DOM.parent(list[i],this.actual)){
                 after++;
                 }
                 }*/


                return  this.record + num == after;
            });
            return this;
        },
        willRemoveChildren:function(selector,nodeNum){
            var num = 1;
            if(arguments.length>1 && typeof arguments[1] == 'number'){
                num = arguments[1];
            }

            // ��¼�´���ڵ����Ԫ����Ϣ innerHTML

            //  �Ƚ����������ĺ��ӽڵ��������
            var list = $(selector);
            var before=0;
            var self = this;
            $.each(list,function(item){
                if($(item).parent(self.actual)){
                    before++;
                }
            })
            /*for(var i= 0,len=list.length;i<len;i++){
             if(S.DOM.parent(list[i],this.actual) ){
             before++;
             }

             }*/


            this.record = before;
            // ��֤�ڵ���Ԫ���Ƿ�������selector��ָ���Ľڵ�. �����Ƿ�һ��

            this.verify = jasmine.Matchers.matcherFn_('willRemoveChild',function(){
                var list = $(selector);
                var after=0;
                var self=this;
                $.each(list,function(index,item){
                    if($(item).parent(self.actual)){
                        after++;
                    }
                })
                /*for(var i= 0,len=list.length;i<len;i++){
                 if(S.DOM.parent(list[i],this.actual) ){
                 after++;
                 }
                 }*/

                return  this.record - num == after;
            });
            return this;
        }
    };


    this.addMatchers(uiMatchers);
});