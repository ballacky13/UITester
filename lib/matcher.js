/*����KISSY 1.2*/
//KISSY.use('dom,sizzle',function(){


var S = KISSY,
    $ = S.all;

/*var support = {
    // IE strips leading whitespace when .innerHTML is used
    leadingWhitespace: ( div.firstChild.nodeType === 3 ),

    // Make sure that tbody elements aren't automatically inserted
    // IE will insert them into empty tables
    tbody: !div.getElementsByTagName("tbody").length,

    // Make sure that link elements get serialized correctly by innerHTML
    // This requires a wrapper element in IE
    htmlSerialize: !!div.getElementsByTagName("link").length,

    // Get the style information from getAttribute
    // (IE uses .cssText instead)
    style: /top/.test( a.getAttribute("style") ),

    // Make sure that URLs aren't manipulated
    // (IE normalizes it by default)
    hrefNormalized: ( a.getAttribute("href") === "/a" ),

    // Make sure that element opacity exists
    // (IE uses filter instead)
    // Use a regex to work around a WebKit issue. See #5145
    opacity: /^0.55/.test( a.style.opacity ),

    // Verify style float existence
    // (IE uses styleFloat instead of cssFloat)
    cssFloat: !!a.style.cssFloat,

    // Make sure that if no value is specified for a checkbox
    // that it defaults to "on".
    // (WebKit defaults to "" instead)
    checkOn: ( input.value === "on" ),

    // Make sure that a selected-by-default option has a working selected property.
    // (WebKit defaults to false instead of true, IE too, if it's in an optgroup)
    optSelected: opt.selected,

    // Test setAttribute on camelCase class. If it works, we need attrFixes when doing get/setAttribute (ie6/7)
    getSetAttribute: div.className !== "t",

    // Tests for enctype support on a form(#6743)
    enctype: !!document.createElement("form").enctype,

    // Makes sure cloning an html5 element does not cause problems
    // Where outerHTML is undefined, this still works
    html5Clone: document.createElement("nav").cloneNode( true ).outerHTML !== "<:nav></:nav>",

    // Will be defined later
    submitBubbles: true,
    changeBubbles: true,
    focusinBubbles: false,
    deleteExpando: true,
    noCloneEvent: true,
    inlineBlockNeedsLayout: false,
    shrinkWrapBlocks: false,
    reliableMarginRight: true,
    pixelMargin: true
};*/


var supportList = {
    reliableHiddenOffsets:true
    //doesNotIncludeMarginInBodyOffset:document.body.offsetTop !== 1
};

/// ��½taobao���߷���

// �ǳ�taobao���߷���

// �����������⹤��

 // �ж�Ԫ���������Ļ0,0 ��λ��
/*function _bodyOffset(body){
    var top = body.offsetTop,
        left = body.offsetLeft;

    if ( supportList.doesNotIncludeMarginInBodyOffset ) {
        top  += parseFloat(S.DOM.css(body, "marginTop") ) || 0;
        left += parseFloat(S.DOM.css(body, "marginLeft") ) || 0;
    }

    return { top: top, left: left };
}

// �ж�Ԫ��b�Ƿ���Ԫ��a��   KISSY��ʵ����
*//*
function contains(a, b) {
    return a.contains ? a != b && a.contains(b) : !!(a.compareDocumentPosition(arg) & 16);
}
*//*

function _getWindow( elem ) {
    return S.isWindow( elem ) ?
        elem :
        elem.nodeType === 9 ?
            elem.defaultView || elem.parentWindow :
            false;
}

function getAbsOffset(elem){
    var getOffset;


    if ( "getBoundingClientRect" in document.documentElement ) {
        getOffset = function( elem, doc, docElem ) {
            var box;

            try {
                box = elem.getBoundingClientRect();
            } catch(e) {}

            // Make sure we're not dealing with a disconnected DOM node
            if ( !box || !jQuery.contains( docElem, elem ) ) {
                return box ? { top: box.top, left: box.left } : { top: 0, left: 0 };
            }

            var body = doc.body,
                win = _getWindow( doc ),
                clientTop  = docElem.clientTop  || body.clientTop  || 0,
                clientLeft = docElem.clientLeft || body.clientLeft || 0,
                scrollTop  = win.pageYOffset || docElem.scrollTop,
                scrollLeft = win.pageXOffset || docElem.scrollLeft,
                top  = box.top  + scrollTop  - clientTop,
                left = box.left + scrollLeft - clientLeft;

            return { top: top, left: left };
        };

    } else {
        getOffset = function( elem, doc, docElem ) {
            if ( !S.DOM.contains( docElem, elem ) ) {
                return { top: 0, left: 0 };
            }
            var point = window.webkitConvertPointFromNodeToPage( elem, new WebKitPoint( 0, 0 ) );
            return { top: point.y, left: point.x };

        };
    }
    var doc = elem&&elem.ownerDocument;
    if(!doc) return null;
    if(elem === doc.body) {
        return _bodyOffset(elem);
    }
    return getOffset(elem,doc,doc.documentElement);
}*/
// ����������֧�����
(function supportCheck(){
    var container,table, td,  conMarginTop, style, positionTopLeftWidthHeight,
        paddingMarginBorderVisibility, paddingMarginBorder, html,
        body = document.getElementsByTagName("body")[0];

    if ( !body ) {
        // Return for frameset docs that don't have a body
        return;
    }

    conMarginTop = 1;
    paddingMarginBorder = "padding:0;margin:0;border:";
    positionTopLeftWidthHeight = "position:absolute;top:0;left:0;width:1px;height:1px;";
    paddingMarginBorderVisibility = paddingMarginBorder + "0;visibility:hidden;";
    style = "style='" + positionTopLeftWidthHeight + paddingMarginBorder + "5px solid #000;";
    html = "<div " + style + "display:block;'><div style='" + paddingMarginBorder + "0;display:block;overflow:hidden;'></div></div>" +
        "<table " + style + "' cellpadding='0' cellspacing='0'>" +
        "<tr><td></td></tr></table>";

    container = document.createElement("div");
    container.style.cssText = paddingMarginBorderVisibility + "width:0;height:0;position:static;top:0;margin-top:" + conMarginTop + "px";
    body.insertBefore( container, body.firstChild );

    // Construct the test element
   var  div = document.createElement("div");
    container.appendChild( div );

    // Check if table cells still have offsetWidth/Height when they are set
    // to display:none and there are still other visible table cells in a
    // table row; if so, offsetWidth/Height are not reliable for use when
    // determining if an element has been hidden directly using
    // display:none (it is still safe to use offsets if a parent element is
    // hidden; don safety goggles and see bug #4512 for more information).
    // (only IE 8 fails this test)
    div.innerHTML = "<table><tr><td style='" + paddingMarginBorder + "0;display:none'></td><td>t</td></tr></table>";
    var  tds = div.getElementsByTagName( "td" );
    var isSupported = ( tds[ 0 ].offsetHeight === 0 );

    tds[ 0 ].style.display = "";
    tds[ 1 ].style.display = "none";

    // Check if empty table cells still have offsetWidth/Height
    // (IE <= 8 fail this test)

    supportList.reliableHiddenOffsets = isSupported && ( tds[ 0 ].offsetHeight === 0 );
} )();

function isHidden(elem){
    var width = elem.offsetWidth,
        height = elem.offsetHeight;

    return ( width === 0 && height === 0 ) || (!supportList.reliableHiddenOffsets && ((elem.style && elem.style.display) || S.DOM.css( elem, "display" )) === "none");
}

function isVisible(elem){
    return !isHidden(elem);
}

function checkVersion (){
    if(S.version !== '1.20'){
        new Error("������kissy 1.2");
        return false;
    }else{
        return true;
    }
}

 // getStyle ���߷���
// KISSY.DOM.css����Ҳ����
function getStyle(selector,styleProp)
{
    var x = $(selector)[0];
    if (x.currentStyle)
        var y = x.currentStyle[styleProp];
    else if (window.getComputedStyle)
        var y = document.defaultView.getComputedStyle(x,null)[styleProp];
    return y;
}

beforeEach(function(){
    if(!checkVersion()){
        return ;
    }

    var uiMatchers = {
        // kissy 1.2 node.hasClass
        toHaveClass: function(className) {
            var nodeList = $(this.actual);
            var result = true;
            for(var i = 0;nodeList[i];i++){
                if(!nodeList.item(i).hasClass(className)){
                    result = false;
                    break;
                }
            }
            return result;
        },
        // ��ȡԪ���Ƿ�ɼ�
        toBeVisible: function() {
            var nodeList = $(this.actual);
            return isVisible(nodeList[0]);
        },

        toBeHidden: function() {

            var nodeList = $(this.actual);
           return isHidden(nodeList[0]);
        },

        toBeSelected: function() {
            var nodeList = $(this.actual);
            return nodeList.filter(':selected').length == nodeList.length;
        },

        toBeChecked: function() {

            var nodeList = $(this.actual);
            return nodeList.filter(':checked').length == nodeList.length;
        },

        toBeEmpty: function() {
            var nodeList = $(this.actual);
            return nodeList.filter(':empty').length == nodeList.length;

        },

        toExist: function() {
            var nodeList = $(this.actual);
            return nodeList.length;
        },
        // ������attributeName ��Ϊ  expectedAttributeValue
        toHaveAttr: function(attributeName, expectedAttributeValue) {
            var nodeList = $(this.actual);
             var result = true;
            for(var i = 0;nodeList[i];i++){
                if(nodeList.item(i).attr(attributeName) != expectedAttributeValue ) {
                    result = false;
                    break;
                }
            }
            return result;

        },

        toHaveProp: function(propertyName, expectedPropertyValue) {
            var nodeList = $(this.actual);
            var result = true;
            for(var i = 0;nodeList[i];i++){
                if(nodeList.item(i).prop(propertyName) != expectedPropertyValue ) {
                    result = false;
                    break;
                }
            }
            return result;

        },

        toHaveId: function(id) {
            var nodeList = $(this.actual);
            return nodeList.item(0).attr('id') == id;
        },
        // ���Կո���ϸ�innerHTMLƥ��
        toHaveHtml: function(html) {
            var nodeList = $(this.actual);
            return S.trim(nodeList.item(0).html()) == S.trim(html);
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
            var temp = S.DOM.create('<div style="display: none;" id="tempHtmlStructure"></div>');
            temp.innerHTML = html;
            $('body').append(temp);
            var expectRecord = [];
            // ����

            serializeHTML($('#tempHtmlStructure')[0],expectRecord);
            S.DOM.remove(temp);
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
            var trimmedText = S.trim(nodeList.item(0).text());
            if (text && S.isFunction(text.test)) {
                return text.test(trimmedText);
            } else {
                return trimmedText == text;
            }
        },

        toHaveValue: function(value) {
            var nodeList = $(this.actual);
            var result = true;
            for(var i = 0;nodeList[i];i++){
                if(nodeList.item(i).val() != value ) {
                    result = false;
                    break;
                }
            }
            return result;



        },




        toBe: function(selector) {
            var nodeList = $(this.actual);
            return nodeList.filter(selector).length;
        },

        toContain: function(selector) {
            return $(selector,this.actual).length;
        },

        toBeDisabled: function(selector){
            var nodeList = $(this.actual);
            return nodeList.filter(':disabled').length == nodeList.length;
        },

        toBeFocused: function(selector) {
            var nodeList = $(this.actual);
            return nodeList.filter(':focus').length == nodeList.length;
        },
        // css �����ж�
         toHaveComputedStyle:function(styleProp,expectValue){
             var expect= expectValue;
             if(styleProp.match(/color/i)){
                 var tempNode = S.DOM.create('<div></div>');
                 $('body').append(tempNode);
                 S.DOM.css(tempNode,styleProp,expectValue);
                 expect = S.DOM.css(tempNode,styleProp);
                 S.DOM.remove(tempNode);
             }

             var nodeList = $(this.actual);
             var result = true;
             for(var i=0;nodeList[i];i++){
                 if(S.DOM.css(nodeList[i],styleProp) !== expect){
                     result = false;
                     break;
                 }
             }
             return result;
         },
        /**
         *
         * @param expectPosition
         */
        atPosition:function(x,y,off){
            var tempOff = 0.1; // ������0.1
            if(arguments[2]&& typeof arguments[2]  == 'number'){
                tempOff = arguments[2];
            }
            var nodeList = $(this.actual);
            var actualPosition = S.DOM.offset(nodeList[0]);
            var heightGap=S.DOM.outerHeight(nodeList[0]) *tempOff;
            var widthGap = S.DOM.outerWidth(nodeList[0]) *tempOff;
            return (Math.abs(actualPosition.top - y) < heightGap) &&( Math.abs(actualPosition.left - x) < widthGap);
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
        willAddChild:function(selector,nodeNum){
            var num = 1;
           if(arguments.length>1 && typeof arguments[1] == 'number'){
               num = arguments[1];
           }
           // ��¼�´���ڵ����Ԫ����Ϣ innerHTML
           this.record = $(selector,this.actual).length;
            // ��֤�ڵ���Ԫ���Ƿ�������selector��ָ���Ľڵ�. �����Ƿ�һ��

           this.verify = jasmine.Matchers.matcherFn_('willAddChild',function(){
               return  this.record + num == $(selector,this.actual).length;
           });
           return this;
        },
        willRemoveChild:function(selector,nodeNum){
            var num = 1;
            if(arguments.length>1 && typeof arguments[1] == 'number'){
                num = arguments[1];
            }
            // ��¼�´���ڵ����Ԫ����Ϣ innerHTML
            this.record = $(selector,this.actual).length;
            // ��֤�ڵ���Ԫ���Ƿ�������selector��ָ���Ľڵ�. �����Ƿ�һ��

            this.verify = jasmine.Matchers.matcherFn_('willRemoveChild',function(){
                return  this.record - num == $(selector,this.actual).length;
            });
            return this;
        }
    };


    this.addMatchers(uiMatchers);
}) ;
//});