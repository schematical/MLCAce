<?php
class MLCAceEventBase extends MJaxEventBase{
    public function Render(){
        $strRendered = sprintf(
            "MLC.Ace.editor.getSession().selection.on('%s', %s);",
            $this->strEventName,
            $this->objAction->Render()
        );
        $this->blnRendered = true;
        return $strRendered;
    }

}
class MLCAceChangeEvent extends MJaxEventBase{
    protected $strEventName = 'change';
}
class MLCAceChangeSelectionEvent extends MJaxEventBase{
    protected $strEventName = 'changeSelection';
}
class MLCAceChangeCursorEvent extends MJaxEventBase{
    protected $strEventName = 'changeCursor';
}



class MLCAceKeyBinding extends MLCAceEventBase{
    protected $strEventName = 'ace_key_binding';
    public function Render(){
        $strRendered = $this->__toJS();
        $this->blnRendered = true;
        return $strRendered;
    }
    protected $strName = null;
    protected $strWinKeys = null;
    protected $strMacKeys = null;
    protected $objAction = null;
    protected $blnReadonly = true;
    public function __construct($strKeys){
        $this->strWinKeys = $strKeys;
        $this->strMacKeys = str_replace('Crtl', 'Command', $strKeys);
    }
    public function __toJS(){
        $strJS = sprintf(
            "MLC.Ace.AddOnLoad(function(){
                MLC.Ace.editor.commands.addCommand({
                    name: '%s',
                    bindKey: {
                        win: '%s',
                        mac: '%s'
                    },
                    exec: %s,
                    readOnly: %s
                });
            });",
            $this->strName,
            $this->strWinKeys,
            $this->strMacKeys,
            $this->objAction->Render(),
            ($this->blnReadonly?'true':'false')
        );
        return $strJS;
    }
}
