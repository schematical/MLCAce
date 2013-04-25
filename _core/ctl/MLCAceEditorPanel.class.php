<?php
class MLCAceEditorPanel extends MJaxPanel{
    public $strCode = null;
    protected $blnInited = false;
    protected $strAceMode = null;
    protected $strAceTheme = null;
    protected $strFileLoc = null;
    protected $strSelected = null;
    protected $pnlOlderSibbling = null;
    public function __construct($objParentControl, $strControlId = null){
        parent::__construct($objParentControl, $strControlId);
        $this->strTemplate = __MLC_ACE_CORE_VIEW__ . '/' . get_class($this) . '.tpl.php';
        $this->objForm->AddHeaderAsset(
            new MJaxJSHeaderAsset(
                MLCApplication::GetAssetUrl('/js/require.js', 'MLCAce')
            )
        );

        /*$this->objForm->AddHeaderAsset(
            new MJaxJSHeaderAsset('http://rawgithub.com/ajaxorg/ace-builds/master/src-noconflict/ace.js')
        );
        $this->objForm->AddHeaderAsset(
            new MJaxJSHeaderAsset(
                MLCApplication::GetAssetUrl('/js/_ace/split.js', 'MLCAce')
            )
        );
        */
        $this->objForm->AddHeaderAsset(
            new MJaxJSHeaderAsset(
                MLCApplication::GetAssetUrl('/js/MLC.Ace.js', 'MLCAce')
            )
        );


    }
    public function GetControlNamespace(){
        return sprintf(
            'MLC.Ace.Editors["%s"]',
            $this->strControlId
        );
    }
    public function SetOlderSibbling($pnlOlderSibbling){
        $this->pnlOlderSibbling = $pnlOlderSibbling;
    }
    public function Render($blnPrint = true, $blnAsAjax = false){
        if(!$this->blnInited){
            $this->objForm->AddJSCall(
                $this->RenderJSInit()
            );

        }elseif($this->blnModified){
            $this->objForm->AddJSCall(
                sprintf(
                    '%s.setValue("%s");',
                    $this->GetControlNamespace(),
                    $this->SanatizeCode()
                )
            );
        }
        parent::Render($blnPrint, $blnAsAjax);

    }
    public function Insert($strCode){
        $this->objForm->AddJSCall(
            sprintf(
                '%s.insert("%s");',
                $this->GetControlNamespace(),
                $this->SanatizeCode($strCode)
            )
        );
    }
    public function Split(MLCAceEditorPanel $pnlSplit){
        $pnlSplit->SetOlderSibbling($pnlSplit);
        $this->objForm->AddJSCall(
            sprintf('
                 MLC.Ace.AddOnLoad(function(){
                    MLC.Ace.Split("%s");
                });',
                $pnlSplit->ControlId
            )
        );
    }
    public function RenderJSInit(){
        $this->objForm->SkipMainWindowRender = true;
        $this->blnInited = true;
        $strJS = '';
        if(is_null($this->pnlOlderSibbling)){
            $strJS .= sprintf(
                'MLC.Ace.Init("%s", "%s");',
                $this->strControlId,
                $this->strAceTheme
            );
        }
        $strJS .= 'MLC.Ace.AddOnLoad(function(){';
        if(!is_null($this->strAceMode)){
            $strJS .= sprintf(
              "%s.getSession().setMode('%s');",
               $this->GetControlNamespace(),
               $this->strAceMode
            );
        }

        if(!is_null($this->strCode)){
            $strJS .= sprintf(
                "%s.setValue('%s');",
                $this->GetControlNamespace(),
                str_replace("\n", '\n', addslashes($this->strCode))
            );
        }
        $strJS .= '});';
        return $strJS;
    }
    public function ParseCodeFromFile($strFileLoc, $mixSaveDriver = null){
        $this->strFileLoc = $strFileLoc;
        if(file_get_contents($this->strFileLoc)){
            $this->strCode = file_get_contents($strFileLoc);
        }
    }
    public function ParsePostData(){
        parent::ParsePostData();
        if(array_key_exists($this->strControlId, $_POST)){
            $this->strSelected = $_POST[$this->strControlId]['selected_text'];
            $this->strCode = $_POST[$this->strControlId]['code'];
        }
    }
    public function Find($strCode, $arrOptions = array()){
        $this->objForm->AddJSCall(
            sprintf(
                '%s.find("%s", %s);',
                $this->GetControlNamespace(),
                $strCode,
                json_encode($arrOptions)
            )
        );
    }

    public function Replace($mixCode){
        if(is_string($mixCode)){
            $strCode = '"' . addslashes($mixCode) . '"';
        }elseif(is_array($mixCode)){
            $strCode = json_encode($mixCode);
        }else{
            throw new MLCWrongTypeException(__FUNCTION__ , $mixCode);
        }
        $this->objForm->AddJSCall(
            sprintf(
                '%s.replace(%s);',
                $this->GetControlNamespace(),
                $strCode
            )
        );
    }
    public function ReplaceAll($mixCode){
        if(is_string($mixCode)){
            $strCode = '"' . addslashes($mixCode) . '"';
        }elseif(is_array($strCode)){
            $strCode = json_encode($mixCode);
        }else{
            throw new MLCWrongTypeException(__FUNCTION__ , $mixCode);
        }
        $this->objForm->AddJSCall(
            sprintf(
                '%s.replaceAll(%s);',
                $this->GetControlNamespace(),
                $strCode
            )
        );
    }
    public function Resize(){
        $this->objForm->AddJSCall(
            sprintf(
                'window.onresize();
                %s.resize();',
                $this->GetControlNamespace()
            )
        );
    }
    public function SetFullScreenResize(){
        $this->objForm->AddJSCall(
            'MLC.Ace.AddOnLoad(function(){
                MLC.Ace.SetFullScreenResize();
            });'
        );
    }

    /////////////////////////
    // Public Properties: GET
    /////////////////////////
    public function __get($strName) {
        switch ($strName) {
            case "AceMode": return $this->strAceMode;
            case "AceTheme": return $this->strAceTheme;
            case "Code": return $this->strCode;
            case "Selected": return $this->strSelected;
            default:
                return parent::__get($strName);
        }
    }

    /////////////////////////
    // Public Properties: SET
    /////////////////////////
    public function __set($strName, $mixValue) {
        $this->blnModified = true;
        switch ($strName) {
            case "AceMode": return $this->strAceMode = $mixValue;
            case "AceTheme": return $this->strAceTheme = $mixValue;
            case "Code": return $this->strCode = $mixValue;
            case "Selected": return $this->strSelected = $mixValue;
            default:
                return parent::__set($strName, $mixValue);

        }
    }
    public function SanatizeCode($strCode = null){
        if(is_null($strCode)){
            $strCode = $this->strCode;
        }
        return trim(str_replace("\r",'\r',str_replace("\n", '\n',addslashes($strCode))));
    }

}