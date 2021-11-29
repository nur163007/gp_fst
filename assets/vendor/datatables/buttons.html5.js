(function (g) {
    "function" === typeof define && define.amd ? define(["jquery", "datatables.net", "datatables.net-buttons"], function (i) {
        return g(i, window, document)
    }) : "object" === typeof exports ? module.exports = function (i, j, t, u) {
        i || (i = window);
        if (!j || !j.fn.dataTable) j = require("datatables.net")(i, j).$;
        j.fn.dataTable.Buttons || require("datatables.net-buttons")(i, j);
        return g(j, i, i.document, t, u)
    } : g(jQuery, window, document)
})(function (g, i, j, t, u, o) {
    function E(a, b) {
        v === o && (v = -1 === y.serializeToString(g.parseXML(F["xl/worksheets/sheet1.xml"])).indexOf("xmlns:r"));
        g.each(b, function (b, c) {
            if (g.isPlainObject(c)) {
                var f = a.folder(b);
                E(f, c)
            } else {
                if (v) {
                    var f = c.childNodes[0], e, h, l = [];
                    for (e = f.attributes.length - 1; 0 <= e; e--) {
                        h = f.attributes[e].nodeName;
                        var n = f.attributes[e].nodeValue;
                        -1 !== h.indexOf(":") && (l.push({name: h, value: n}), f.removeAttribute(h))
                    }
                    e = 0;
                    for (h = l.length; e < h; e++)n = c.createAttribute(l[e].name.replace(":", "_dt_b_namespace_token_")), n.value = l[e].value, f.setAttributeNode(n)
                }
                f = y.serializeToString(c);
                v && (-1 === f.indexOf("<?xml") && (f = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' +
                    f), f = f.replace(/_dt_b_namespace_token_/g, ":"));
                f = f.replace(/<row xmlns="" /g, "<row ").replace(/<cols xmlns="">/g, "<cols>");
                a.file(b, f)
            }
        })
    }

    function p(a, b, d) {
        var c = a.createElement(b);
        d && (d.attr && g(c).attr(d.attr), d.children && g.each(d.children, function (a, b) {
            c.appendChild(b)
        }), d.text && c.appendChild(a.createTextNode(d.text)));
        return c
    }

    function N(a, b) {
        var d = a.header[b].length, c;
        a.footer && a.footer[b].length > d && (d = a.footer[b].length);
        for (var f = 0, e = a.body.length; f < e && !(c = a.body[f][b].toString().length, c > d &&
        (d = c), 40 < d); f++);
        return 5 < d ? d : 5
    }

    var r = g.fn.dataTable, q;
    var h = "undefined" !== typeof self && self || "undefined" !== typeof i && i || this.content;
    if ("undefined" !== typeof navigator && /MSIE [1-9]\./.test(navigator.userAgent)) q = void 0; else {
        var w = h.document.createElementNS("http://www.w3.org/1999/xhtml", "a"), O = "download" in w,
            G = /Version\/[\d\.]+.*Safari/.test(navigator.userAgent), z = h.webkitRequestFileSystem,
            H = h.requestFileSystem || z || h.mozRequestFileSystem, P = function (a) {
                (h.setImmediate || h.setTimeout)(function () {
                    throw a;
                }, 0)
            }, A = 0, B = function (a) {
                setTimeout(function () {
                    "string" === typeof a ? (h.URL || h.webkitURL || h).revokeObjectURL(a) : a.remove()
                }, 4E4)
            }, C = function (a, b, d) {
                for (var b = [].concat(b), c = b.length; c--;) {
                    var f = a["on" + b[c]];
                    if ("function" === typeof f)try {
                        f.call(a, d || a)
                    } catch (e) {
                        P(e)
                    }
                }
            }, I = function (a) {
                return /^\s*(?:text\/\S*|application\/xml|\S*\/\S*\+xml)\s*;.*charset\s*=\s*utf-8/i.test(a.type) ? new Blob(["﻿", a], {type: a.type}) : a
            }, J = function (a, b, d) {
                d || (a = I(a));
                var c = this, d = a.type, f = !1, e, g, l = function () {
                        C(c, ["writestart", "progress",
                            "write", "writeend"])
                    }, n = function () {
                        if (g && G && "undefined" !== typeof FileReader) {
                            var b = new FileReader;
                            b.onloadend = function () {
                                var a = b.result;
                                g.location.href = "data:attachment/file" + a.slice(a.search(/[,;]/));
                                c.readyState = c.DONE;
                                l()
                            };
                            b.readAsDataURL(a);
                            c.readyState = c.INIT
                        } else {
                            if (f || !e) e = (h.URL || h.webkitURL || h).createObjectURL(a);
                            g ? g.location.href = e : h.open(e, "_blank") === o && G && (h.location.href = e);
                            c.readyState = c.DONE;
                            l();
                            B(e)
                        }
                    }, m = function (a) {
                        return function () {
                            if (c.readyState !== c.DONE)return a.apply(this, arguments)
                        }
                    },
                    i = {create: !0, exclusive: !1}, s;
                c.readyState = c.INIT;
                b || (b = "download");
                if (O) e = (h.URL || h.webkitURL || h).createObjectURL(a), setTimeout(function () {
                    w.href = e;
                    w.download = b;
                    var a = new MouseEvent("click");
                    w.dispatchEvent(a);
                    l();
                    B(e);
                    c.readyState = c.DONE
                }); else {
                    h.chrome && (d && "application/octet-stream" !== d) && (s = a.slice || a.webkitSlice, a = s.call(a, 0, a.size, "application/octet-stream"), f = !0);
                    z && "download" !== b && (b += ".download");
                    if ("application/octet-stream" === d || z) g = h;
                    H ? (A += a.size, H(h.TEMPORARY, A, m(function (d) {
                        d.root.getDirectory("saved",
                            i, m(function (d) {
                                var e = function () {
                                    d.getFile(b, i, m(function (b) {
                                        b.createWriter(m(function (d) {
                                            d.onwriteend = function (a) {
                                                g.location.href = b.toURL();
                                                c.readyState = c.DONE;
                                                C(c, "writeend", a);
                                                B(b)
                                            };
                                            d.onerror = function () {
                                                var a = d.error;
                                                a.code !== a.ABORT_ERR && n()
                                            };
                                            ["writestart", "progress", "write", "abort"].forEach(function (a) {
                                                d["on" + a] = c["on" + a]
                                            });
                                            d.write(a);
                                            c.abort = function () {
                                                d.abort();
                                                c.readyState = c.DONE
                                            };
                                            c.readyState = c.WRITING
                                        }), n)
                                    }), n)
                                };
                                d.getFile(b, {create: false}, m(function (a) {
                                    a.remove();
                                    e()
                                }), m(function (a) {
                                    a.code ===
                                    a.NOT_FOUND_ERR ? e() : n()
                                }))
                            }), n)
                    }), n)) : n()
                }
            }, k = J.prototype;
        "undefined" !== typeof navigator && navigator.msSaveOrOpenBlob ? q = function (a, b, d) {
            d || (a = I(a));
            return navigator.msSaveOrOpenBlob(a, b || "download")
        } : (k.abort = function () {
            this.readyState = this.DONE;
            C(this, "abort")
        }, k.readyState = k.INIT = 0, k.WRITING = 1, k.DONE = 2, k.error = k.onwritestart = k.onprogress = k.onwrite = k.onabort = k.onerror = k.onwriteend = null, q = function (a, b, d) {
            return new J(a, b, d)
        })
    }
    r.fileSave = q;
    var x = function (a, b) {
        var d = "*" === a.filename && "*" !== a.title &&
        a.title !== o ? a.title : a.filename;
        "function" === typeof d && (d = d());
        -1 !== d.indexOf("*") && (d = g.trim(d.replace("*", g("title").text())));
        d = d.replace(/[^a-zA-Z0-9_\u00A1-\uFFFF\.,\-_ !\(\)]/g, "");
        return b === o || !0 === b ? d + a.extension : d
    }, Q = function (a) {
        var b = "Sheet1";
        a.sheetName && (b = a.sheetName.replace(/[\[\]\*\/\\\?\:]/g, ""));
        return b
    }, R = function (a) {
        a = a.title;
        "function" === typeof a && (a = a());
        return -1 !== a.indexOf("*") ? a.replace("*", g("title").text() || "Exported data") : a
    }, K = function (a) {
        return a.newline ? a.newline : navigator.userAgent.match(/Windows/) ?
            "\r\n" : "\n"
    }, L = function (a, b) {
        for (var d = K(b), c = a.buttons.exportData(b.exportOptions), f = b.fieldBoundary, e = b.fieldSeparator,
                 g = RegExp(f, "g"), l = b.escapeChar !== o ? b.escapeChar : "\\", h = function (a) {
                for (var b = "", c = 0,
                         d = a.length; c < d; c++)0 < c && (b += e), b += f ? f + ("" + a[c]).replace(g, l + f) + f : a[c];
                return b
            }, i = b.header ? h(c.header) + d : "", j = b.footer && c.footer ? d + h(c.footer) : "", s = [], D = 0,
                 k = c.body.length; D < k; D++)s.push(h(c.body[D]));
        return {str: i + s.join(d) + j, rows: s.length}
    }, M = function () {
        return -1 !== navigator.userAgent.indexOf("Safari") &&
            -1 === navigator.userAgent.indexOf("Chrome") && -1 === navigator.userAgent.indexOf("Opera")
    };
    try {
        var y = new XMLSerializer, v
    } catch (S) {
    }
    var F = {
        "_rels/.rels": '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/></Relationships>',
        "xl/_rels/workbook.xml.rels": '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/><Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/></Relationships>',
        "[Content_Types].xml": '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types"><Default Extension="xml" ContentType="application/xml" /><Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml" /><Default Extension="jpeg" ContentType="image/jpeg" /><Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml" /><Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml" /><Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml" /></Types>',
        "xl/workbook.xml": '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"><fileVersion appName="xl" lastEdited="5" lowestEdited="5" rupBuild="24816"/><workbookPr showInkAnnotation="0" autoCompressPictures="0"/><bookViews><workbookView xWindow="0" yWindow="0" windowWidth="25600" windowHeight="19020" tabRatio="500"/></bookViews><sheets><sheet name="" sheetId="1" r:id="rId1"/></sheets></workbook>',
        "xl/worksheets/sheet1.xml": '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships" xmlns:mc="http://schemas.openxmlformats.org/markup-compatibility/2006" mc:Ignorable="x14ac" xmlns:x14ac="http://schemas.microsoft.com/office/spreadsheetml/2009/9/ac"><sheetData/></worksheet>',
        "xl/styles.xml": '<?xml version="1.0" encoding="UTF-8"?><styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:mc="http://schemas.openxmlformats.org/markup-compatibility/2006" mc:Ignorable="x14ac" xmlns:x14ac="http://schemas.microsoft.com/office/spreadsheetml/2009/9/ac"><fonts count="5" x14ac:knownFonts="1"><font><sz val="11" /><name val="Calibri" /></font><font><sz val="11" /><name val="Calibri" /><color rgb="FFFFFFFF" /></font><font><sz val="11" /><name val="Calibri" /><b /></font><font><sz val="11" /><name val="Calibri" /><i /></font><font><sz val="11" /><name val="Calibri" /><u /></font></fonts><fills count="6"><fill><patternFill patternType="none" /></fill><fill/><fill><patternFill patternType="solid"><fgColor rgb="FFD9D9D9" /><bgColor indexed="64" /></patternFill></fill><fill><patternFill patternType="solid"><fgColor rgb="FFD99795" /><bgColor indexed="64" /></patternFill></fill><fill><patternFill patternType="solid"><fgColor rgb="ffc6efce" /><bgColor indexed="64" /></patternFill></fill><fill><patternFill patternType="solid"><fgColor rgb="ffc6cfef" /><bgColor indexed="64" /></patternFill></fill></fills><borders count="2"><border><left /><right /><top /><bottom /><diagonal /></border><border diagonalUp="false" diagonalDown="false"><left style="thin"><color auto="1" /></left><right style="thin"><color auto="1" /></right><top style="thin"><color auto="1" /></top><bottom style="thin"><color auto="1" /></bottom><diagonal /></border></borders><cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0" /></cellStyleXfs><cellXfs count="56"><xf numFmtId="0" fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="1" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="2" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="3" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="4" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="0" fillId="2" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="1" fillId="2" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="2" fillId="2" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="3" fillId="2" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="4" fillId="2" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="0" fillId="4" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="1" fillId="4" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="2" fillId="4" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="3" fillId="4" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="4" fillId="4" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="0" fillId="4" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="1" fillId="4" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="2" fillId="4" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="3" fillId="4" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="4" fillId="4" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="0" fillId="5" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="1" fillId="5" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="2" fillId="5" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="3" fillId="5" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="4" fillId="5" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="0" fillId="0" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="1" fillId="0" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="2" fillId="0" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="3" fillId="0" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="4" fillId="0" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="0" fillId="2" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="1" fillId="2" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="2" fillId="2" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="3" fillId="2" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="4" fillId="2" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="0" fillId="3" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="1" fillId="3" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="2" fillId="3" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="3" fillId="3" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="4" fillId="3" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="0" fillId="4" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="1" fillId="4" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="2" fillId="4" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="3" fillId="4" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="4" fillId="4" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="0" fillId="5" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="1" fillId="5" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="2" fillId="5" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="3" fillId="5" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="4" fillId="5" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1" xfId="0" applyAlignment="1"><alignment horizontal="left"/></xf><xf numFmtId="0" fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1" xfId="0" applyAlignment="1"><alignment horizontal="center"/></xf><xf numFmtId="0" fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1" xfId="0" applyAlignment="1"><alignment horizontal="right"/></xf><xf numFmtId="0" fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1" xfId="0" applyAlignment="1"><alignment horizontal="fill"/></xf><xf numFmtId="0" fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1" xfId="0" applyAlignment="1"><alignment textRotation="90"/></xf><xf numFmtId="0" fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1" xfId="0" applyAlignment="1"><alignment wrapText="1"/></xf></cellXfs><cellStyles count="1"><cellStyle name="Normal" xfId="0" builtinId="0" /></cellStyles><dxfs count="0" /><tableStyles count="0" defaultTableStyle="TableStyleMedium9" defaultPivotStyle="PivotStyleMedium4" /></styleSheet>'
    };
    r.ext.buttons.copyHtml5 = {
        className: "buttons-copy buttons-html5", text: function (a) {
            return a.i18n("buttons.copy", "Copy")
        }, action: function (a, b, d, c) {
            var a = L(b, c), f = a.str,
                d = g("<div/>").css({height: 1, width: 1, overflow: "hidden", position: "fixed", top: 0, left: 0});
            c.customize && (f = c.customize(f, c));
            c = g("<textarea readonly/>").val(f).appendTo(d);
            if (j.queryCommandSupported("copy")) {
                d.appendTo(b.table().container());
                c[0].focus();
                c[0].select();
                try {
                    var e = j.execCommand("copy");
                    d.remove();
                    if (e) {
                        b.buttons.info(b.i18n("buttons.copyTitle",
                            "Copy to clipboard"), b.i18n("buttons.copySuccess", {
                            1: "Copied one row to clipboard",
                            _: "Copied %d rows to clipboard"
                        }, a.rows), 2E3);
                        return
                    }
                } catch (h) {
                }
            }
            e = g("<span>" + b.i18n("buttons.copyKeys", "Press <i>ctrl</i> or <i>⌘</i> + <i>C</i> to copy the table data<br>to your system clipboard.<br><br>To cancel, click this message or press escape.") + "</span>").append(d);
            b.buttons.info(b.i18n("buttons.copyTitle", "Copy to clipboard"), e, 0);
            c[0].focus();
            c[0].select();
            var l = g(e).closest(".dt-button-info"), i = function () {
                l.off("click.buttons-copy");
                g(j).off(".buttons-copy");
                b.buttons.info(!1)
            };
            l.on("click.buttons-copy", i);
            g(j).on("keydown.buttons-copy", function (a) {
                27 === a.keyCode && i()
            }).on("copy.buttons-copy cut.buttons-copy", function () {
                i()
            })
        }, exportOptions: {}, fieldSeparator: "\t", fieldBoundary: "", header: !0, footer: !1
    };
    r.ext.buttons.csvHtml5 = {
        bom: !1,
        className: "buttons-csv buttons-html5",
        available: function () {
            return i.FileReader !== o && i.Blob
        },
        text: function (a) {
            return a.i18n("buttons.csv", "CSV")
        },
        action: function (a, b, d, c) {
            a = L(b, c).str;
            b = c.charset;
            c.customize &&
            (a = c.customize(a, c));
            !1 !== b ? (b || (b = j.characterSet || j.charset), b && (b = ";charset=" + b)) : b = "";
            c.bom && (a = "﻿" + a);
            q(new Blob([a], {type: "text/csv" + b}), x(c), !0)
        },
        filename: "*",
        extension: ".csv",
        exportOptions: {},
        fieldSeparator: ",",
        fieldBoundary: '"',
        escapeChar: '"',
        charset: null,
        header: !0,
        footer: !1
    };
    r.ext.buttons.excelHtml5 = {
        className: "buttons-excel buttons-html5", available: function () {
            return i.FileReader !== o && (t || i.JSZip) !== o && !M() && y
        }, text: function (a) {
            return a.i18n("buttons.excel", "Excel")
        }, action: function (a, b,
                             d, c) {
            var f = 0, a = function (a) {
                return g.parseXML(F[a])
            }, e = a("xl/worksheets/sheet1.xml"), h = e.getElementsByTagName("sheetData")[0], a = {
                _rels: {".rels": a("_rels/.rels")},
                xl: {
                    _rels: {"workbook.xml.rels": a("xl/_rels/workbook.xml.rels")},
                    "workbook.xml": a("xl/workbook.xml"),
                    "styles.xml": a("xl/styles.xml"),
                    worksheets: {"sheet1.xml": e}
                },
                "[Content_Types].xml": a("[Content_Types].xml")
            }, b = b.buttons.exportData(c.exportOptions), l, j, d = function (a) {
                l = f + 1;
                j = p(e, "row", {attr: {r: l}});
                for (var b = 0, c = a.length; b < c; b++) {
                    for (var d =
                        b, i = ""; 0 <= d;)i = String.fromCharCode(d % 26 + 65) + i, d = Math.floor(d / 26) - 1;
                    d = i + "" + l;
                    if (null === a[b] || a[b] === o) a[b] = "";
                    "number" === typeof a[b] || a[b].match && g.trim(a[b]).match(/^-?\d+(\.\d+)?$/) && !g.trim(a[b]).match(/^0\d+/) ? d = p(e, "c", {
                        attr: {
                            t: "n",
                            r: d
                        }, children: [p(e, "v", {text: a[b]})]
                    }) : (i = !a[b].replace ? a[b] : a[b].replace(/[\x00-\x09\x0B\x0C\x0E-\x1F\x7F-\x9F]/g, ""), d = p(e, "c", {
                        attr: {
                            t: "inlineStr",
                            r: d
                        }, children: {row: p(e, "is", {children: {row: p(e, "t", {text: i})}})}
                    }));
                    j.appendChild(d)
                }
                h.appendChild(j);
                f++
            };
            g("sheets sheet",
                a.xl["workbook.xml"]).attr("name", Q(c));
            c.customizeData && c.customizeData(b);
            c.header && (d(b.header, f), g("row c", e).attr("s", "2"));
            for (var m = 0, k = b.body.length; m < k; m++)d(b.body[m], f);
            c.footer && b.footer && (d(b.footer, f), g("row:last c", e).attr("s", "2"));
            d = p(e, "cols");
            g("worksheet", e).prepend(d);
            m = 0;
            for (k = b.header.length; m < k; m++)d.appendChild(p(e, "col", {
                attr: {
                    min: m + 1,
                    max: m + 1,
                    width: N(b, m),
                    customWidth: 1
                }
            }));
            c.customize && c.customize(a);
            b = new (t || i.JSZip);
            d = {type: "blob", mimeType: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"};
            E(b, a);
            b.generateAsync ? b.generateAsync(d).then(function (a) {
                q(a, x(c))
            }) : q(b.generate(d), x(c))
        }, filename: "*", extension: ".xlsx", exportOptions: {}, header: !0, footer: !1
    };
    r.ext.buttons.pdfHtml5 = {
        className: "buttons-pdf buttons-html5",
        available: function () {
            return i.FileReader !== o && (u || i.pdfMake)
        },
        text: function (a) {
            return a.i18n("buttons.pdf", "PDF")
        },
        action: function (a, b, d, c) {
            K(c);
            var a = b.buttons.exportData(c.exportOptions), f = [];
            c.header && f.push(g.map(a.header, function (a) {
                return {
                    text: "string" === typeof a ? a : a + "",
                    style: "tableHeader"
                }
            }));
            for (var e = 0, h = a.body.length; e < h; e++)f.push(g.map(a.body[e], function (a) {
                return {text: "string" === typeof a ? a : a + "", style: e % 2 ? "tableBodyEven" : "tableBodyOdd"}
            }));
            c.footer && a.footer && f.push(g.map(a.footer, function (a) {
                return {text: "string" === typeof a ? a : a + "", style: "tableFooter"}
            }));
            a = {
                pageSize: c.pageSize,
                pageOrientation: c.orientation,
                content: [{table: {headerRows: 1, body: f}, layout: "noBorders"}],
                styles: {
                    tableHeader: {bold: !0, fontSize: 11, color: "white", fillColor: "#2d4154", alignment: "center"},
                    tableBodyEven: {},
                    tableBodyOdd: {fillColor: "#f3f3f3"},
                    tableFooter: {bold: !0, fontSize: 11, color: "white", fillColor: "#2d4154"},
                    title: {alignment: "center", fontSize: 15},
                    message: {}
                },
                defaultStyle: {fontSize: 10}
            };
            c.message && a.content.unshift({
                text: "function" == typeof c.message ? c.message(b, d, c) : c.message,
                style: "message",
                margin: [0, 0, 0, 12]
            });
            c.title && a.content.unshift({text: R(c, !1), style: "title", margin: [0, 0, 0, 12]});
            c.customize && c.customize(a, c);
            b = (u || i.pdfMake).createPdf(a);
            "open" === c.download && !M() ? b.open() : b.getBuffer(function (a) {
                a =
                    new Blob([a], {type: "application/pdf"});
                q(a, x(c))
            })
        },
        title: "*",
        filename: "*",
        extension: ".pdf",
        exportOptions: {},
        orientation: "portrait",
        pageSize: "A4",
        header: !0,
        footer: !1,
        message: null,
        customize: null,
        download: "download"
    };
    return r.Buttons
});
