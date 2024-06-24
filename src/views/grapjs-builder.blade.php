<div id='gjs'>
    {!! $body !!}
</div>
<input type='hidden' name='{{$attr["name"]}}' id='description_grapjs'/>

<link rel="stylesheet" href="//unpkg.com/grapesjs/dist/css/grapes.min.css">
<script src="//unpkg.com/grapesjs"></script>
<script src="https://unpkg.com/grapesjs-preset-newsletter"></script>

<script src="https://unpkg.com/grapesjs-blocks-basic"></script>
<script src="https://unpkg.com/grapesjs-blocks-basic"></script>
<script src="https://unpkg.com/grapesjs-custom-code"></script>
<script src="https://unpkg.com/grapesjs-plugin-forms"></script>
<link href="https://unpkg.com/grapesjs-rte-extensions/dist/grapesjs-rte-extensions.min.css" rel="stylesheet">
<script src="https://unpkg.com/grapesjs-rte-extensions"></script>
<link href="https://unpkg.com/grapick/dist/grapick.min.css" rel="stylesheet">
<script src="https://unpkg.com/grapesjs-style-bg"></script>
<script>
    const editor = grapesjs.init({
        forceClass: false,
      plugins: [ 'gjs-blocks-basic','grapesjs-custom-code','grapesjs-plugin-forms','grapesjs-rte-extensions','grapesjs-style-bg'],
      pluginsOpts: {
        'grapesjs-preset-newsletter': {
          // options
        },
        'grapesjs-style-bg': {}
      },

  // Indicate where to init the editor. You can also pass an HTMLElement
  container: '#gjs',
  // Get the content for the canvas directly from the element
  // As an alternative we could use: `components: '<h1>Hello World Component!</h1>'`,
  fromElement: true,
  canvas: {
    styles: JSON.parse('{!! json_encode( $styles ) !!}'),
    scripts: JSON.parse('{!! json_encode( $scripts ) !!}')
  },

  // Size of the editor
  // height: '300px',
  width: 'auto',
  // Disable the storage manager for the moment
  storageManager: false,
  assetManager: {
    upload: '{{url("/abn-cms/upload")}}',
    params: {"_token": '{{csrf_token()}}'},
    uploadName: 'upload',
    assets:JSON.parse('{!! json_encode( $assets ) !!}')
  }
  // Avoid any default panel


});
editor.on('asset:remove',function (asset)  {
    console.log("asset",asset)
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function() {

    }
    xhttp.open("GET", "{{url("abn-cms/delete-upload")}}?id="+asset.id, true);
    xhttp.send();

});
editor.on('all',function ()  {
    const mjml = editor.getHtml();
    css = editor.getCss()
    document.getElementById("description_grapjs").value="<style>"+css+"</style>" + mjml
});
var pfx = editor.getConfig().stylePrefix;
var modal = editor.Modal;
var cmdm = editor.Commands;
var codeViewer = editor.CodeManager.getViewer('CodeMirror').clone();
var pnm = editor.Panels;
var container = document.createElement('div');
var btnEdit = document.createElement('button');

codeViewer.set({
    codeName: 'htmlmixed',
    readOnly: 0,
    theme: 'hopscotch',
    autoBeautify: true,
    autoCloseTags: true,
    autoCloseBrackets: true,
    lineWrapping: true,
    styleActiveLine: true,
    smartIndent: true,
    indentWithTabs: true
});

btnEdit.innerHTML = 'Edit';
btnEdit.type="button"
btnEdit.className = pfx + 'btn-prim ' + pfx + 'btn-import';
btnEdit.onclick = function() {
    var code = codeViewer.editor.getValue();
    editor.DomComponents.getWrapper().set('content', '');
    editor.setComponents(code.trim());
    modal.close();
};

cmdm.add('html-edit', {
    run: function(editor, sender) {
        sender && sender.set('active', 0);
        var viewer = codeViewer.editor;
        modal.setTitle('Edit code');
        if (!viewer) {
            var txtarea = document.createElement('textarea');
            container.appendChild(txtarea);
            container.appendChild(btnEdit);
            codeViewer.init(txtarea);
            viewer = codeViewer.editor;
        }
        var InnerHtml = editor.getHtml();
        var Css = editor.getCss();
        modal.setContent('');
        modal.setContent(container);
        codeViewer.setContent(InnerHtml + "<style>" + Css + '</style>');
        modal.open();
        viewer.refresh();
    }
});

pnm.addButton('options',
    [
        {
            id: 'edit',
            className: 'fa fa-edit',
            command: 'html-edit',
            attributes: {
                title: 'Edit'
            }
        }
    ]
);
 const bm = editor.Blocks; // `Blocks` is an alias of `BlockManager`

// Add a new Block
// let shortcodes = JSON.parse(JSON.stringify({!! json_encode( $shortcodes ) !!}))
// console.log("shortcodes",shortcodes)
// for(shortcode in shortcodes){
//     const block = bm.add(shortcode, {

//         label: shortcode,
//         content: '<shortcode name=""></shortcode>',

//         });
// }
let plugins = JSON.parse(JSON.stringify({!! json_encode( $plugins ) !!}))




async function loadPlugin(plugins){
    for(plugin of plugins){
        if(plugin.type && plugin.type.identifier){
          plugin.content=  '<'+plugin.type.identifier+' data-gjs-type="'+plugin.type.identifier+'">'+  plugin.content + '</'+plugin.type.identifier+'>';
        }
    const block = bm.add(plugin.label, plugin);
    console.log("plugin",plugin)

    if(plugin.type){
        let  scriptText ='';
        for(let sct in plugin.type.scripts){
                let script  =plugin.type.scripts[sct];

                if(script.src){
                    await load('script',script.src);
                }
                else{
                    scriptText+= script.text
                }
        }
    if(plugin.type.customScript){
        let fn =plugin.type['fn'];
          eval(scriptText);

          let fnVal=  eval(`window.${fn}()`)

        editor.DomComponents.addType(plugin.type.identifier, fnVal ? fnVal :{} )

    }
    else{
        let defaults = JSON.stringify( {...plugin.type.defaults,script: scriptText})

                eval(  `editor.DomComponents.addType('${plugin.type.identifier}',   {
                model: {

                    defaults:${defaults}
                    }

                }  )`);
    }

    }
}
}


async function load(type,src,attrs={}){

        const myPromise = new Promise((resolve, reject) => {
        const script = document.createElement(type);
            script.onload = ()=>{
             resolve();
            };
            if(type=='script')
                script.src = src;
            else
                script.href = src;

            for(let attr in attrs){
                script[attr]=attrs[attr]
            }
            document.body.appendChild(script);
        });
        return myPromise;

}

loadPlugin(plugins)

editor.DomComponents.addType('shortcode', {
    isComponent: el => el.tagName == 'SHORTCODE',
    model: {
      defaults: {
        traits: [
          // Strings are automatically converted to text types
        //   'name', // Same as: { type: 'text', name: 'name' }
          'placeholder',
          {
            type: 'select', // Type of the trait
            label: 'Name', // The label you will see in Settings
            name: 'name', // The name of the attribute/property to use on component
            options: [
              { id: 'text', name: 'Text'},
              { id: 'email', name: 'Email'},
              { id: 'password', name: 'Password'},
              { id: 'number', name: 'Number'},
            ]
          }],

      },
    },
});

function returnHtml(){
}
</script>

