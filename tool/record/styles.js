﻿(function () {
    //重置setTimeout,setInterval
    // ajax等异步方法


    $(document).ready(function () {

        var allMutationRecords = [];

        var filterStyles = function (styles) {
            var tempArray = [];
            for (var p in  styles) {
                if (uitest.configs.styles[p]) {
                    tempArray.push(styles[p])
                }
            }
            return tempArray;
        }

        var mergeCSSRules = function (CSSRules, target) {
            var merged = {};
            if (!CSSRules)CSSRules = [];

            for (var i = 0; i < CSSRules.length; i++) {
                var s = CSSRules[i].style;
                for (var j = 0; j < s.length; j++) {
                    var sname = s.getPropertyShorthand(s[j]) || s[j]

                    if (uitest.configs.styles[sname]) {
                        merged[sname] = $(target).css(sname);
                    }

                }
            }
            return merged;

        }


        var createTestCase = function (target) {

            var testCase = 'describe("测试元素样式",function(){\n';
            var selector = uitest.inner.elToSelector(target);
            // var computedStyle =window.getComputedStyle(target);
            //   var styles = filterStyles(computedStyle);
            var styles = mergeCSSRules(window.getMatchedCSSRules(target), target)
            if (Object.getOwnPropertyNames(styles).length == 0) {

                uitest.inner.outterCall("appendCaseCode", ["//" + selector + " 没有定义样式"])

                return;
            }
            //window.getMatchedCSSRules
            // mergeCSSRules(window.getMatchedCSSRules(target))


            testCase += '  it("' + selector + '"' + ', function(){\n';

            var expects = []


            for (var p in  styles) {


                var name = p;
                var value = styles[p];
                if (value) {
                    expects.push('    expect("' + selector + '").toHaveComputedStyle("' + name + '","' + value + '");\n');
                }

            }
            if (expects.length == 0) {
                uitest.inner.outterCall("appendCaseCode", ["//" + selector + " 没有定义样式"])
                return;
            }

            for (var i = 0; i < expects.length; i++) {
                testCase += expects[i];
            }


            //所有事件都要停止，避免影响测试结果，
            // 包括hover的处理


            //showMsg(123);
            testCase += '  })\n})\n'
            uitest.inner.outterCall("appendCaseCode", [testCase])

        }


        //事件类型
        document.body.addEventListener("click", function (e) {

            if (uitest.configs.caseType == "style") {
                var target = e.target;

                window.setTimeout(function () {
                    createTestCase(target);
                    allMutationRecords = [];
                }, 10)

            }


        }, true, true)


    })


})();