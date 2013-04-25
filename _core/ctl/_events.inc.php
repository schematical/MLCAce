<?php
class MLCAceEventBase extends MJaxEventBase{
    protected $strAceSelector = null;
    public function Init($mixTarget, $objAction){
        parent::Init($mixTarget, $objAction);
        $this->strAceSelector = $this->objControl->GetControlNamespace();
    }
    public function Render(){

            $strRendered = sprintf(
                "MLC.Ace.AddOnLoad(function(){
                    %s.getSession().selection.on('%s', %s);
                });",
                $this->strAceSelector,
                $this->strEventName,
                $this->objAction->Render()
            );
            $this->blnRendered = true;
            return $strRendered;

    }
    public function RenderUnbind(){

    }

}
class MLCAceChangeEvent extends MLCAceEventBase{
    protected $strEventName = 'change';
}
class MLCAceChangeSelectionEvent extends MLCAceEventBase{
    protected $strEventName = 'changeSelection';
}
class MLCAceChangeCursorEvent extends MLCAceEventBase{
    protected $strEventName = 'changeCursor';
}

class MLCAceChangeSelectionEnd extends MLCAceEventBase{
    protected $strEventName = 'mlc-ace-changeCursorEnd';

    public function Render(){

        $strRendered = sprintf(
            "MLC.Ace.AddOnLoad(function(){
                %s.on('mouseup',function(objEvent){
                     var strSelected = %s.session.getTextRange(%s.getSelectionRange());
                     console.log(strSelected);
                    if(strSelected.length > 1){
                        (%s)(objEvent);
                    }
                });
            });",
            $this->strAceSelector,
            $this->strAceSelector,
            $this->strAceSelector,
            $this->objAction->Render()
        );
        $this->blnRendered = true;
        return $strRendered;

    }
    public function RenderUnbind(){

    }

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
                %s.commands.addCommand({
                    name: '%s',
                    bindKey: {
                        win: '%s',
                        mac: '%s'
                    },
                    exec: %s,
                    readOnly: %s
                });
            });",
            $this->strAceSelector,
            $this->strName,
            $this->strWinKeys,
            $this->strMacKeys,
            $this->objAction->Render(),
            ($this->blnReadonly?'true':'false')
        );
        return $strJS;
    }
}
