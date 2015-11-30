
function Crop(id,image_width,ratio,pre_container,display_inputs) {
    this._id = id;
    this._image_width = image_width;
    this._ratio = ratio;
    this._pre_container = '#' + pre_container;
    var is_display = display_inputs || false;

    this._display_inputs = is_display ? 'display:block' : 'display:none';
    console.log(display_inputs,is_display,this._display_inputs);
    this._elems  = {
        'crop_elem' : '#' + this._id + '-image .img-container',
        'crop_container' :  '#' + this._id + '-image',
        'crop_button' : '#crop-button-' + this._id,
        'crop_form' : '#crop-form-' + this._id
    };
}

Crop.prototype.start = function(e)
{
    this.clear();
    this._evt = e;
    this.generateHtml();
    var self = this;
    self._elems['crop_img'] = self._elems['crop_elem'] + ' img';
    $(self._elems['crop_container']).html('<div class="img-container"></div>');
    this._evt.stopPropagation();
    this._evt.preventDefault();

    this._file = this._evt.dataTransfer !== undefined ? this._evt.dataTransfer.files[0] : this._evt.target.files[0];
    this._reader = new FileReader();
    this._reader.onload = this.readerOnload();
    this._reader.readAsDataURL(this._file);
};

//Crop.prototype.cropButtonCallback = function(image)
//{   var self = this;
//    return function() {
//        self._jCropApi[self._id].destroy();
//        $(self._elems['crop_container']).html('<div class="img-container"><img></div>');
//        console.log(self._elems['crop_img']);
//        var img = $(self._elems['crop_img'])[0];
//        console.log(img);
//        var canvas = document.createElement('canvas');
//        var x1 = $('#x1-' + self._id).val();
//        var y1 = $('#y1-' + self._id).val();
//        var x2 = $('#x2-' + self._id).val();
//        var y2 = $('#y2-' + self._id).val();
//        var w = $('#w-' + self._id).val();
//        var h = $('#h-' + self._id).val();
//
//        var scale = image.width / self._image_width;
//        canvas.width = w;
//        canvas.height = h;
//        var x1_new = Math.round(x1 * scale);
//        var y1_new = Math.round(y1 * scale);
//        var w_new = Math.round(w * scale);
//        var h_new = Math.round(h * scale);
//        console.log(x1_new);
//        console.log(x1);
//        console.log(y1_new);
//        console.log(y1);
//        console.log(w_new);
//        console.log(w );
//        console.log(h_new);
//        console.log(h );
//        canvas.getContext('2d').drawImage(image,x1_new ,y1_new, w_new ,h_new,0,0,w ,h);
//        $(self._elems['crop_container']).css({'height': (h) + 'px','width':w + 'px'});
//        $(self._elems['crop_elem']).css({'height': (h) + 'px','width':w + 'px'});
//        $(img).attr('src',(canvas.toDataURL()));
//        $(this).hide();
//    }
//};
//
//Crop.prototype.initCropButton = function(image)
//{
//    var self = this;
//    $(self._elems['crop_button']).show();
//    $(self._elems['crop_button']).html('Save Crop');
//    $(self._elems['crop_button']).css('display','block');
//    $(self._elems['crop_button']).on('click',self.cropButtonCallback(image));
//};

Crop.prototype.init = function (image) {
    this.initJcrop(image);
};

Crop.prototype.initJcrop = function(image) {
    var self = this;
    var width = self._image_width,
        height = image.height * (self._image_width / image.width),
        new_width = height * self._ratio > width ? width : height * self._ratio,
        new_height = new_width / self._ratio;

    function updatePreview(c)
    {

        $('#x1-' + self._id).val(c.x < 0 ? 0 : c.x);
        $('#y1-' + self._id).val(c.y < 0 ? 0 : c.y);
        $('#x2-' + self._id).val(c.x2 < 0 ? 0 : c.x2);
        $('#y2-' + self._id).val(c.y2 < 0 ? 0 : c.y2);
        $('#w-' + self._id).val(c.w < 0 ? 0 : c.w) ;
        $('#h-' + self._id).val(c.h < 0 ? 0 : c.h);
        $('#scale-' +  self._id).val(image.width / self._image_width);
    }

    function clearCoords()
    {
        $(self._elems['crop_button'] + ' input').val('');
    }

    $(self._elems['crop_elem']).Jcrop({
        onChange: updatePreview,
        onSelect: updatePreview,
        onRelease : clearCoords,
        minSize : [30, 10 / self._ratio],
        aspectRatio: self._ratio,
        setSelect : [0,0,new_width,new_height]
    },function(){
        var bounds = this.getBounds(),
            boundx = bounds[0],
            boundy = bounds[1];
        self._jCropApi = this;
    });
};

Crop.prototype.readerOnload = function(){
    var self = this;
    return function(e) {
        var image = new Image();
        image.src = e.target.result;
        image.onload = function() {
            self._canvas = document.createElement('canvas');
            self._canvas.width = self._image_width;
            self._canvas.height = image.height * (self._image_width / image.width);
            var ctx = self._canvas.getContext('2d');
            ctx.drawImage(image, 0, 0, self._canvas.width, self._canvas.height);

            $(self._elems['crop_container']).css({'height': (self._canvas.height) + 'px','width':self._canvas.width + 'px'});
            $(self._elems['crop_elem']).css({'height': (self._canvas.height) + 'px','width':self._canvas.width + 'px'});
            $(self._elems['crop_elem']).html(['<img src="', self._canvas.toDataURL(), '"/>'].join(''));

            var img = $(self._elems['crop_img'])[0];

            self.init(image);
        }
    }
};
Crop.prototype.generateHtml = function()
{
    var self = this;

    Jaml.register('crop-form',function(data){
        div({id: data.id + '-image'},
            div({class:'img-container'})
        );
        div({id:'crop-form-' + data.id,style: data.display_inputs},
            input({id:'x1-' + data.id,type:'text',name:'Crop['+ data.id +'][x1]'}),
            input({id:'x2-' + data.id,type:'text',name:'Crop['+ data.id +'][x2]'}),
            input({id:'y1-' + data.id,type:'text',name:'Crop['+ data.id +'][y1]'}),
            input({id:'y2-' + data.id,type:'text',name:'Crop['+ data.id +'][y2]'}),
            input({id:'w-' + data.id ,type:'text',name:'Crop['+ data.id +'][w]'}),
            input({id:'h-'+ data.id,type:'text',name:'Crop['+ data.id +'][h]'}),
            input({id:'scale-'+ data.id,type:'text',name:'Crop['+ data.id +'][scale]'})
        );
    });

    var html = Jaml.render('crop-form',{id : self._id,
        display_inputs : self._display_inputs});
    $(html).insertAfter($(self._pre_container));

};

Crop.prototype.clear = function(){
    if(this._jCropApi !== undefined)
    {
        this._jCropApi.destroy();
    }
    if(this._canvas !== undefined)
    {
        var canvas = this._canvas;
        $(canvas).remove();
    }
    $('#crop-form-' + this._id).remove();
    $('#' + this._id + '-image').remove();
};
