if(typeof MLC == 'undefined'){
    MLC = {};
}
MLC.Ace = {
    arrOnLoad:[],
    Init:function(strControlId, strTheme){
        requirejs.config({
            //By default load any module IDs from js/lib
            baseUrl: '/assets/MLCAce/js'
        });

        MLC.Ace.jEle = $('#'+ strControlId);
        require(["ace/ace", "ace/split"], function (ace, split) {
            MLC.Ace.split = new split.Split(MLC.Ace.jEle[0], strTheme, 1);
            MLC.Ace.split.setOrientation(MLC.Ace.split.BESIDE);


            MLC.Ace.editor = MLC.Ace.split.$editors[0];
            ;
            console.log(MLC.Ace.editor);
            for(var intIndex in MLC.Ace.arrOnLoad){
                MLC.Ace.arrOnLoad[intIndex]();
            }

            function onResize() {
                var left = $('#divSideNav').css('width');
                var width = document.documentElement.clientWidth - left;
                MLC.Ace.jEle.css('width', width + "px");
                MLC.Ace.jEle.css('height',document.documentElement.clientHeight + "px");
                MLC.Ace.jEle.css('left', left + "px");
                MLC.Ace.split.resize();
                $(MLC.Ace.split.$container).css('top', 40);
                $(MLC.Ace.split.$container).css('left', left);

                //consoleEl.style.width = width + "px";
                //cmdLine.resize();
            }

            window.onresize = onResize;
            onResize();

        });
    },
    AddOnLoad:function(funOnLoad){
        MLC.Ace.arrOnLoad[MLC.Ace.arrOnLoad.length] = funOnLoad;
    },
    TriggerControlEvent:function(objEvent, strSelector, strEvent, objData){
        if(typeof objData == 'undefined'){
            objData = new Object();
        }
        objData.selected_text = MLC.Ace.editor.session.getTextRange(
            MLC.Ace.editor.getSelectionRange()
        );
        objData.code = MLC.Ace.editor.getValue();
        MJax.TriggerControlEvent(objEvent, strSelector, strEvent, objData);
    },
    Split:function(){
        MLC.Ace.split.setSplits(2);
        MLC.Ace.editor2 = MLC.Ace.split.getEditor(1);
        MLC.Ace.editor2.setValue(MLC.Ace.editor.getValue());

        var session = MLC.Ace.split.getEditor(0).session;
        //var newSession = MLC.Ace.split.setSession(session, 1);

        MLC.Ace.split.on("focus", function(editor) {

        });
       /* MLC.Ace.editor.on("change", function(editor) {
            var strCode = MLC.Ace.editor.getValue();
            MLC.Ace.split.$editors[1].setValue(
                strCode
            );

        });*/
        MLC.Ace.split.on("blur", function() {
            //jsSnippets.snippetText = editor.getValue();
            //saveSnippets();
        });




        MLC.Ace.split.$editors[0].destroy();
        MLC.Ace.split.$editors[1].destroy();

    },
    BindSplitScroll:function(){
        var s1 = MLC.Ace.split.$editors[0].session;
        var s2 = MLC.Ace.split.$editors[1].session;
        s1.on('changeScrollTop', function(pos) {s2.setScrollTop(pos)});
        s2.on('changeScrollTop', function(pos) {s1.setScrollTop(pos)});
        s1.on('changeScrollLeft', function(pos) {s2.setScrollLeft(pos)});
        s2.on('changeScrollLeft', function(pos) {s1.setScrollLeft(pos)});
    }
};
