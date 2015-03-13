if ($.fn.pagination){
    $.fn.pagination.defaults.beforePageText = 'Sayfa';
    $.fn.pagination.defaults.afterPageText = ' / {pages}';
    $.fn.pagination.defaults.displayMsg = '{from} ile {to} arasﾄｱ gﾃｶsteriliyor, toplam {total} kayﾄｱt';
}
if ($.fn.datagrid){
    $.fn.panel.defaults.loadingMessage = "Yﾃｼkleniyor...";
}

if ($.fn.datagrid){
    $.fn.datagrid.defaults.loadingMessage = "Yﾃｼkleniyor...";
    $.fn.datagrid.defaults.loadMsg = 'ﾄｰﾅ殕eminiz Yapﾄｱlﾄｱyor, lﾃｼtfen bekleyin ...';
}
if ($.fn.treegrid && $.fn.datagrid){
    $.fn.treegrid.defaults.loadMsg = $.fn.datagrid.defaults.loadMsg;
}
if ($.messager){
    $.messager.defaults.ok = 'Tamam';
    $.messager.defaults.cancel = 'ﾄｰptal';
}
if ($.fn.validatebox){
    $.fn.validatebox.defaults.missingMessage = 'Bu alan zorunludur.';
    $.fn.validatebox.defaults.rules.email.message = 'Lﾃｼtfen geﾃｧerli bir email adresi giriniz.';
    $.fn.validatebox.defaults.rules.url.message = 'Lﾃｼtfen geﾃｧerli bir URL giriniz.';
    $.fn.validatebox.defaults.rules.length.message = 'Lﾃｼtfen {0} ile {1} arasﾄｱnda bir deﾄ歹r giriniz.';
    $.fn.validatebox.defaults.rules.remote.message = 'Lﾃｼtfen bu alanﾄｱ dﾃｼzeltiniz.';
}
if ($.fn.numberbox){
    $.fn.numberbox.defaults.missingMessage = 'Bu alan zorunludur.';
}
if ($.fn.combobox){
    $.fn.combobox.defaults.missingMessage = 'Bu alan zorunludur.';
}
if ($.fn.combotree){
    $.fn.combotree.defaults.missingMessage = 'Bu alan zorunludur.';
}
if ($.fn.combogrid){
    $.fn.combogrid.defaults.missingMessage = 'Bu alan zorunludur.';
}
if ($.fn.calendar){
    $.fn.calendar.defaults.weeks = ['Pz','Pt','Sa','ﾃ㌢','Pe','Cu','Ct'];
    $.fn.calendar.defaults.months = ['Oca', 'ﾅ柆b', 'Mar', 'Nis', 'May', 'Haz', 'Tem', 'Aﾄ殷', 'Eyl', 'Eki', 'Kas', 'Ara'];
}
if ($.fn.datebox){
    $.fn.datebox.defaults.currentText = 'Bugﾃｼn';
    $.fn.datebox.defaults.closeText = 'Kapat';
    $.fn.datebox.defaults.okText = 'Tamam';
    $.fn.datebox.defaults.missingMessage = 'Bu alan zorunludur.';
}
if ($.fn.datetimebox && $.fn.datebox){
    $.extend($.fn.datetimebox.defaults,{
        currentText: $.fn.datebox.defaults.currentText,
        closeText: $.fn.datebox.defaults.closeText,
        okText: $.fn.datebox.defaults.okText,
        missingMessage: $.fn.datebox.defaults.missingMessage
    });
    
    $.fn.datebox.defaults.formatter=function(date){
        var y=date.getFullYear();
        var m=date.getMonth()+1;
        var d=date.getDate();
        if(m<10){m="0"+m;}
        if(d<10){d="0"+d;}
        return d+"."+m+"."+y;
    };
}
