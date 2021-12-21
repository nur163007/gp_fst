function commentsLogScript(comments, aBuyer, aSupplier){
    // Buyer's comments log
    $(aBuyer+'title').hide();
    $(aBuyer).hide();
    var commentsLog = '';
    for(var i=0; i<comments.length; i++){
        if(comments[i]['fromgroup']!=_supplier){
            /*if(commentsLog.length>0){commentsLog += '<hr />';}
            commentsLog += '<span class="comment-author">' + comments[i]['rolename'] + ': <span class="text-primary">' + comments[i]['username'] + '</span> <i class="icon wb-arrow-right"></i> '+ comments[i]['torole'] +'</span> <div class="comment-meta">'+comments[i]['msgon'] + '</div>';
            if (comments[i]['stage']) {
                commentsLog += '<div class="comment-content"><span style="color: #F2A654">Stage: </span><span class="text-primary">' + comments[i]['stage'] + '</span></div>';
            }
            commentsLog += '<div class="comment-content"><h5>' + comments[i]['title'] + '</h5></div>';*/
            if (comments[i]['msg']!=null) {
                commentsLog += '<div class="comment-content">&bull;&nbsp;' + htmlspecialchars_decode(comments[i]['msg']) + '</div>';
            }
        }
    }
    if(commentsLog!=''){
        $('#buyersmsg').html(commentsLog);
        $('#buyersmsgtitle').show();
        $('#buyersmsg').show();
    }
    if(aSupplier!=""){
        // Supplier's comments log
        $(aSupplier+'title').hide();
        $(aSupplier).hide();
        var commentsLog = '';
        for(var i=0; i<comments.length; i++){
            if(comments[i]['fromgroup']==_supplier){
                /*if(commentsLog.length>0){commentsLog += '<hr />';}
                commentsLog += '<span class="comment-author">' + comments[i]['rolename'] + ': <span class="text-primary">' + comments[i]['username'] + '</span> <i class="icon wb-arrow-right"></i> '+ comments[i]['torole'] +'</span> <div class="comment-meta">'+comments[i]['msgon'] + '</div>';
                if (comments[i]['stage']) {
                    commentsLog += '<div class="comment-content"><span style="color: #F2A654">Stage: </span><span class="text-primary">' + comments[i]['stage'] + '</span></div>';
                }
                commentsLog += '<div class="comment-content"><h5>' + comments[i]['title'] + '</h5></div>';*/
                if(comments[i]['msg']!=null) {
                    commentsLog += '<div class="comment-content">&bull;&nbsp;' + htmlspecialchars_decode(comments[i]['msg']) + '</div>';
                }
            }
        }
        if(commentsLog!=''){
            $('#suppliersmsg').html(commentsLog);
            $('#suppliersmsgtitle').show();
            $('#suppliersmsg').show();
        }
        }
}


function commentsTFOLogScript(commentsTFO, Buyer, Supplier){
    // Buyer's comments log
    $(Buyer+'title').hide();
    $(Buyer).hide();
    var commentsLog = '';
    for(var i=0; i<commentsTFO.length; i++){
        if(commentsTFO[i]['fromgroup']!=_supplier){
            /*if(commentsLog.length>0){commentsLog += '<hr />';}
            commentsLog += '<span class="comment-author">' + commentsTFO[i]['rolename'] + ': <span class="text-primary">' + commentsTFO[i]['username'] + '</span> <i class="icon wb-arrow-right"></i> '+ commentsTFO[i]['torole'] +'</span> <div class="comment-meta">'+commentsTFO[i]['msgon'] + '</div>';
            if (commentsTFO[i]['stage']) {
                commentsLog += '<div class="comment-content"><span style="color: #F2A654">Stage: </span><span class="text-primary">' + commentsTFO[i]['stage'] + '</span></div>';
            }
            commentsLog += '<div class="comment-content"><h5>' + commentsTFO[i]['title'] + '33</h5></div>';*/
            if (commentsTFO[i]['msg']!=null) {
                commentsLog += '<div class="comment-content">' + htmlspecialchars_decode(commentsTFO[i]['msg']) + '</div>';
            }
        }
    }
    if(commentsLog!=''){
        $('#buyersmsgTFO').html(commentsLog);
        $('#buyerFeedback').show();
        $('#buyersmsgTFO').show();
    }
    if(Supplier!=""){
        // Supplier's comments log
        $(Supplier+'title').hide();
        $(Supplier).hide();
        var commentsLog = '';
        for(var i=0; i<commentsTFO.length; i++){
            if(commentsTFO[i]['fromgroup']==_supplier){
                /*if(commentsLog.length>0){commentsLog += '<hr />';}
                commentsLog += '<span class="comment-author">' + commentsTFO[i]['rolename'] + ': <span class="text-primary">' + commentsTFO[i]['username'] + '</span> <i class="icon wb-arrow-right"></i> '+ commentsTFO[i]['torole'] +'</span> <div class="comment-meta">'+commentsTFO[i]['msgon'] + '</div>';
                if (commentsTFO[i]['stage']) {
                    commentsLog += '<div class="comment-content"><span style="color: #F2A654">Stage: </span><span class="text-primary">' + commentsTFO[i]['stage'] + '</span></div>';
                }
                commentsLog += '<div class="comment-content"><h5>' + commentsTFO[i]['title'] + '11</h5></div>';*/
                if(commentsTFO[i]['msg']!=null) {
                    commentsLog += '<div class="comment-content">' + htmlspecialchars_decode(commentsTFO[i]['msg']) + '</div>';
                }
            }
        }
        if(commentsLog!=''){
            $('#suppliersmsgTFO').html(commentsLog);
            $('#supplierFeedback').show();
            $('#suppliersmsgTFO').show();
        }
    }
}

/*
function commentsTFOLogScript(commentsTFO, Buyer, Supplier){
    // Buyer's comments log
    $(Buyer+'title').hide();
    $(Buyer).hide();
    var commentsLog = '';
    for(var i=0; i<commentsTFO.length; i++){
        if(commentsTFO[i]['fromgroup']!=_supplier){
            if(commentsLog.length>0){commentsLog += '<hr />';}
            commentsLog += '<span class="comment-author">' + commentsTFO[i]['rolename'] + ': <span class="text-primary">' + commentsTFO[i]['username'] + '</span> <i class="icon wb-arrow-right"></i> '+ commentsTFO[i]['torole'] +'</span> <div class="comment-meta">'+commentsTFO[i]['msgon'] + '</div>';
            if (commentsTFO[i]['stage']) {
                commentsLog += '<div class="comment-content"><span style="color: #F2A654">Stage: </span><span class="text-primary">' + commentsTFO[i]['stage'] + '</span></div>';
            }
            commentsLog += '<div class="comment-content"><h5>' + commentsTFO[i]['title'] + '</h5></div>';
            if (commentsTFO[i]['msg']!=null) {
                commentsLog += '<div class="comment-content">' + htmlspecialchars_decode(commentsTFO[i]['msg']) + '</div>';
            }
        }
    }
    if(commentsLog!=''){
        $('#buyersmsgTFO').html(commentsLog);
        $('#buyerFeedback').show();
        $('#buyersmsgTFO').show();
    }
    if(Supplier!=""){
        // Supplier's comments log
        $(Supplier+'title').hide();
        $(Supplier).hide();
        var commentsLog = '';
        for(var i=0; i<commentsTFO.length; i++){
            if(commentsTFO[i]['fromgroup']==_supplier){
                if(commentsLog.length>0){commentsLog += '<hr />';}
                commentsLog += '<span class="comment-author">' + commentsTFO[i]['rolename'] + ': <span class="text-primary">' + commentsTFO[i]['username'] + '</span> <i class="icon wb-arrow-right"></i> '+ commentsTFO[i]['torole'] +'</span> <div class="comment-meta">'+commentsTFO[i]['msgon'] + '</div>';
                if (commentsTFO[i]['stage']) {
                    commentsLog += '<div class="comment-content"><span style="color: #F2A654">Stage: </span><span class="text-primary">' + commentsTFO[i]['stage'] + '</span></div>';
                }
                commentsLog += '<div class="comment-content"><h5>' + commentsTFO[i]['title'] + '</h5></div>';
                if(commentsTFO[i]['msg']!=null) {
                    commentsLog += '<div class="comment-content">' + htmlspecialchars_decode(commentsTFO[i]['msg']) + '</div>';
                }
            }
        }
        if(commentsLog!=''){
            $('#suppliersmsgTFO').html(commentsLog);
            $('#supplierFeedback').show();
            $('#suppliersmsgTFO').show();
        }
    }
}

*/

function attachmentLogScript(attachments, elem, col, filter, filterType, mailattach) {

    col = col || 1;
    filter = filter || "";
    mailattach = mailattach || "";
    filterType = filterType || 1;

    var attachmentHtml = '';
    var attachedBy = '';

    if (filter == "") {
        for (var i = 0; i < attachments.length; i++) {
            if (attachments[i]['rolename'] != attachedBy) {
                if (attachedBy != '') {
                    if (col == 2) {
                        attachmentHtml += `</div>`;
                    }
                    attachmentHtml += `</div>`;
                }
                attachedBy = attachments[i]['rolename'];
                if (col == 2) {
                    attachmentHtml += `<div class="col-sm-6">`;
                }
                attachmentHtml += `<h4 class="well well-sm example-title margin-bottom-5">${attachedBy}'s Attachments</h4>
                    <div class="form-group">`;
            }
            //attachmentHtml += '<label class="col-sm-5 control-label">'+attachments[i]['title']+'</label>'+
            attachmentHtml += `<label class="col-sm-1 control-label">&nbsp;</label>
                <div class="col-sm-11">
                    <label class="control-label"><i class="icon fa-${attachments[i]['ext'].toLowerCase()}"></i>&nbsp;&nbsp;
                         <a href="download-attachment/${attachments[i][0]}" title="${attachments[i]['filename']}" target="_blank">${attachments[i]['title']}</a>
                   </label>
                </div>`;
        }
    } else {
        for (var i = 0; i < attachments.length; i++) {
            if (filterType == 1) {
                if (filter.indexOf(attachments[i]['title']) > -1) {
                    if (attachments[i]['rolename'] != attachedBy) {
                        if (attachedBy != '') {
                            if (col == 2) {
                                attachmentHtml += `</div>`;
                            }
                            attachmentHtml += `</div>`;
                        }
                        attachedBy = attachments[i]['rolename'];
                        if (col == 2) {
                            attachmentHtml += `<div class="col-sm-6">`;
                        }
                        attachmentHtml += `<h4 class="well well-sm example-title margin-bottom-5">${attachedBy}'s Attachments</h4>
                            <div class="form-group">`;
                    }
                    //console.log(attachments);
                    //attachmentHtml += '<label class="col-sm-5 control-label">'+attachments[i]['title']+'</label>'+
                    attachmentHtml += `<label class="col-sm-1 control-label">&nbsp;</label>
                        <div class="col-sm-11">
                            <label class="control-label"><i class="icon fa-${attachments[i]['ext'].toLowerCase()}"></i>&nbsp;&nbsp;
                                 <a href="download-attachment/${attachments[i][0]}" title="${attachments[i]['filename']}" target="_blank">${attachments[i]['title']}</a>
                           </label>
                        </div>`;
                }
            } else {
                if (filter.indexOf(attachments[i]['title']) < 0) {
                    if (attachments[i]['rolename'] != attachedBy) {
                        if (attachedBy != '') {
                            if (col == 2) {
                                attachmentHtml += `</div>`;
                            }
                            attachmentHtml += `</div>`;
                        }
                        attachedBy = attachments[i]['rolename'];
                        if (col == 2) {
                            attachmentHtml += `<div class="col-sm-6">`;
                        }
                        attachmentHtml += `<h4 class="well well-sm example-title margin-bottom-5">${attachedBy}'s Attachments</h4>
                            <div class="form-group">`;
                    }
                    // attachmentHtml += '<label class="col-sm-5 control-label">'+attachments[i]['title']+'</label>'+
                    attachmentHtml += `<label class="col-sm-1 control-label">&nbsp;</label>
                        <div class="col-sm-11">
                            <label class="control-label"><i class="icon fa-${attachments[i]['ext'].toLowerCase()}"></i>&nbsp;&nbsp;
                                <a href="download-attachment/${attachments[i][0]}" title="${attachments[i]['filename']}" target="_blank">${attachments[i]['title']}</a>
                           </label>
                        </div>`;
                }
            }
        }
    }

    $(elem).html(attachmentHtml);

}

function attachmentPRA(attachments, elem, col, filter, filterType, mailattach) {

    col = col || 1;
    filter = filter || "";
    mailattach = mailattach || "";
    filterType = filterType || 1;

    var attachmentHtml = '';
    var attachedBy = '';

    if (filter == "") {
        for (var i = 0; i < attachments.length; i++) {
            if (attachments[i]['rolename'] != attachedBy) {
                if (attachedBy != '') {
                    if (col == 2) {
                        attachmentHtml += `</div>`;
                    }
                    attachmentHtml += `</div>`;
                }
                attachedBy = attachments[i]['rolename'];
                if (col == 2) {
                    attachmentHtml += `<div class="col-sm-6">`;
                }
                attachmentHtml += `<h4 class="well well-sm example-title margin-bottom-5">${attachedBy}'s Attachments</h4>
                    <div class="form-group">`;
            }
            //attachmentHtml += '<label class="col-sm-5 control-label">'+attachments[i]['title']+'</label>'+
            attachmentHtml += `<label class="col-sm-1 control-label">&nbsp;</label>
                <div class="col-sm-11">
                    <label class="control-label"><i class="icon fa-${attachments[i]['ext'].toLowerCase()}"></i>&nbsp;&nbsp;
                         <a href="download-attachment/${attachments[i][0]}" title="${attachments[i]['filename']}" target="_blank">${attachments[i]['title']}</a>
                   </label>
                </div>`;
        }
    } else {
        for (var i = 0; i < attachments.length; i++) {
            if (filterType == 1) {
                if (filter.indexOf(attachments[i]['title']) > -1) {
                    if (attachments[i]['rolename'] != attachedBy) {
                        if (attachedBy != '') {
                            if (col == 2) {
                                attachmentHtml += `</div>`;
                            }
                            attachmentHtml += `</div>`;
                        }
                        attachedBy = attachments[i]['rolename'];
                        if (col == 2) {
                            attachmentHtml += `<div class="col-sm-6">`;
                        }
                        attachmentHtml += `<h4 class="well well-sm example-title margin-bottom-5">${attachedBy}'s Attachments</h4>
                            <div class="form-group">`;
                    }
                    //console.log(attachments);
                    //attachmentHtml += '<label class="col-sm-5 control-label">'+attachments[i]['title']+'</label>'+
                    attachmentHtml += `<label class="col-sm-1 control-label">&nbsp;</label>
                        <div class="col-sm-11">
                            <label class="control-label"><i class="icon fa-${attachments[i]['ext'].toLowerCase()}"></i>&nbsp;&nbsp;
                                 <a href="download-attachment/${attachments[i][0]}" title="${attachments[i]['filename']}" target="_blank">${attachments[i]['title']}</a>
                           </label>
                        </div>`;
                }
            } else {
                if (filter.indexOf(attachments[i]['title']) < 0) {
                    if (attachments[i]['rolename'] != attachedBy) {
                        if (attachedBy != '') {
                            if (col == 2) {
                                attachmentHtml += `</div>`;
                            }
                            attachmentHtml += `</div>`;
                        }
                        attachedBy = attachments[i]['rolename'];
                        if (col == 2) {
                            attachmentHtml += `<div class="col-sm-6">`;
                        }
                        attachmentHtml += `<h4 class="well well-sm example-title margin-bottom-5">${attachedBy}'s Attachments</h4>
                            <div class="form-group">`;
                    }
                    // attachmentHtml += '<label class="col-sm-5 control-label">'+attachments[i]['title']+'</label>'+
                    attachmentHtml += `<label class="col-sm-1 control-label">&nbsp;</label>
                        <div class="col-sm-11">
                            <label class="control-label"><i class="icon fa-${attachments[i]['ext'].toLowerCase()}"></i>&nbsp;&nbsp;
                                <a href="download-attachment/${attachments[i][0]}" title="${attachments[i]['filename']}" target="_blank">${attachments[i]['title']}</a>
                           </label>
                        </div>`;
                }
            }
        }
    }

    $(elem).html(attachmentHtml);

}
function getImplementedBy(opt){
    //alert(opt);
    if(opt==0){
        return "GP";
    } else if(opt==1){
        return "Supplier";
    } else if(opt==2){
        return "Other";
    }
}
