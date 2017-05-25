 
 
 
class AppEvent { 
    
    constructor(type, contextData = null) {
        this.type = type;
        this.contextData = contextData;
    }
    
} 


class ImageDTO {
    constructor(id, imageDataStr, width, height) {
        this.id = id;
        this.imageDataStr = imageDataStr;
        this.width = width;
        this.height = height;
    }
    
    
    resetimageData(imageDataStr) {
        this.imageDataStr = imageDataStr;
        this.imageData = false;
    }
    
    getimageData() {
        if(!(this.imageData instanceof ImageData) && this.imageDataStr) {
            this.imageData = UtilityImageData.unpack(this.imageDataStr, this.width, this.height);
        }
        console.log(this.imageData, this.imageDataStr);
        return this.imageData;
    }
}


class UtilityImageData {
  
    static pack(imageData) {
        let height = imageData.height,
            width = imageData.width,
            data = imageData.data,
            result = [],
            i, y=0, x=0, r, g, b, rgb, lastrgb = -1, size,len =data.length;
      
        for (i = 0; i < len; i += 4) {
            
            r = data[((width * y) + x) * 4];
            g = data[((width * y) + x) * 4 + 1];
            b = data[((width * y) + x) * 4 + 2];  
            
            rgb =  (r << 16) | (g << 8) | (b);
            
            if(rgb == lastrgb) {
                size ++;
            } else {
                if(lastrgb != -1) {
                    result.push( lastrgb + '-' + size);
                }
                lastrgb = rgb;
                size = 1;
            } 
            
            x ++;
            if(x >= width) {
                x = 0;
                y ++;  
            }
        }
        if(size > 0 && lastrgb != -1) {
            result.push(  lastrgb + '-' + size);
        }
        console.log(result);
        return result.join('.');
    }    
    
    static unpack(data, width, height ) {
        let len = width * height * 4, dataImage = new Uint8ClampedArray(len), 
            spl = data.split('.'), k, vl,bit,cbit,i, index = 0;
        for(k in spl) {
            vl = spl[k];
            [bit, cbit] = vl.split('-'); 
           
            for(i = 0; i < cbit; i ++) {
               dataImage[index ++] =  bit >> 16 & 0xFF;
               dataImage[index ++] =  bit >> 8 & 0xFF;
               dataImage[index ++] =  bit & 0xFF;
               dataImage[index ++] =  255;
            }
        }
        
        return new ImageData(dataImage, width, height);
    }    
}
 
 
class App {
    
    
    constructor() {
        
        this.cid = undefined;
        
        const self = this; 
        
        this.addObserver('Draftsman.save', (e) => {
            
            let data = e.contextData, strImage = this.imageDataToString(data), query = '';
            
            if(self.cid) {
                query = '&id=' + self.cid;
            }
            
            $.post('/index.php?r=api/save' + query, {
                width:      data.width,
                height:     data.height,
                strImage:   strImage,
            }, (result) => {
                 if(!result.success) {
                     console.log("Что то пошло не так", result.errors);
                 } else { 
                     
                     if(!self.cid) {//add new 
                         self.fire( new AppEvent('Draftsman.add', result));
                     } else {
                         self.fire( new AppEvent('Draftsman.update', result));
                     }
                     
                     self.cid = result.id; 
                     
                 }
            });
        });
        
        this.addObserver('DraftsmanImage.select', (e) => {
            let data = e.contextData;
            this.cid = data.id;
            
            if(self.draftsman2d) {
                self.draftsman2d.putImageData(data.getimageData(), 0, 0, 0, 0, data.width, data.height);
            } else {
                console.log("Что то пошло не так" );
            }
            
        });
        
        this.addObserver('DraftsmanImage.delete', (e) => {
             let data = e.contextData;
             $.get('/index.php?r=api/delete&id=' +  data.id, function(list){
                    self.loadList();
            }); 
        });
        
        this.addObserver('Draftsman.init', (e) => {  
            self.draftsman2d = e.contextData.getConvas2D();
        });  
        
        this.addObserver('Draftsman.add', (e) => {
            self.loadList();
        });
    }
    
    
    addObserver(type, handler) {
        if(!this.obs) {
            this.obs = {};
        }
        if(!this.obs[type]) {
            this.obs[type] = [];
        }
        this.obs[type].push(handler);
    }
    
    
    fire(e) {
        if(!(e instanceof AppEvent) || !this.obs[e.type]) {
            return;
        }
        
        this.obs[e.type].map((handler)=>{
            handler.apply(null, [e]);
        })
    }
    
    
    stringToImageData(  data, width, height ) {         
        return UtilityImageData.unpack(data, width, height);
    }
    
    imageDataToString(imageData){
        return UtilityImageData.pack(imageData);
    } 
    
    loadList() { 
        const self = this;
        
        $.getJSON('/index.php?r=api/list', function(list){
            if(list.length > 0) { 
                let images = list.map((item) => {
                    return new ImageDTO(item.id, item.data, item.width, item.height );
                });
                 
                self.fire( new AppEvent('App.loadimages', images)); 
            }
        }); 
    }
    
    init() {  
        
        ReactDOM.render(
                
                 <div className="row">
                    <div className="col-sm-6">
                    <Draftsman width="320" height="320" /> 
                    </div>
                    <div className="col-sm-6">
                     <DraftsmanImageList/>
                    </div>
                 </div>, 
         document.getElementById('draw')); 
         
         this.loadList();  
    }
}

var app = new App();
 

class Draftsman extends React.Component {
  
  static get defaultProps() {
    return {
       width: 50,
       height: 50
    }
  }
    
 constructor(props) {
    super(props);
    this.state = { }; 
    this.beginDraw = false; 
  }
    
  onStopBeginDraw(e) { 
      this.beginDraw = false;
  } 
  
  onStartBeginDraw(e) {  
      this.beginDraw = true;
      this.drawPoint(e); 
  } 
  
  onMoveBeginDraw(e) { 
      if(this.beginDraw) {             
           this.drawPoint(e);          
      }
      return false;
  } 
  
  getBrushSize() {
      return 5;
  }
  
  drawPoint(e) {
      let cn= this.getConvas2D();
      let rect = this.getConvas().getBoundingClientRect();
      let x = e.clientX - rect.left;
      let y = e.clientY - rect.top
           
      
      cn.beginPath();
      cn.fillStyle = document.getElementById('color_choser') ? document.getElementById('color_choser').value : '#ffffff'; 
      cn.arc(x,y, this.getBrushSize(),0,Math.PI*2,true);
      cn.closePath();
      cn.fill();
  }
  
  getConvas() {
      return this.canvas;
  }
   
  getConvas2D() {
      if(!this.convas2d) {
          this.convas2d = this.getConvas().getContext('2d');
      }      
      return this.convas2d;
  }
  
  
  clear() {
    let cn= this.getConvas2D();  
    cn.fillStyle="#000000";
    cn.fillRect(0,0,this.getWidth(),this.getHeight());
    cn.fillStyle="#aaaaaa";
    cn.strokeRect(0,0,this.getWidth(),this.getHeight()); 
  }
  
  
  save() {
      let cn= this.getConvas2D(); 
      let data = cn.getImageData(0,0, this.getHeight(), this.getHeight());
      app.fire(new AppEvent('Draftsman.save', data));
  }
  
  componentDidMount() { 
      let canvas = this.getConvas();
      let self = this;      
      
      canvas.addEventListener("mousedown", (e)=>{ self.onStartBeginDraw.call(self, e); }, false);
      canvas.addEventListener("mouseleave", (e)=>{ self.onStopBeginDraw.call(self, e); }, false);
      canvas.addEventListener("mouseup", (e)=>{ self.onStopBeginDraw.call(self, e); }, false);
      canvas.addEventListener("mousemove", (e)=>{ self.onMoveBeginDraw.call(self, e); }, false);
      
      this.clear();      
      
      app.fire(new AppEvent('Draftsman.init', self));
  }
  
  
  componentWillUnmount() {
    //TODO
  }
  
  
  getWidth() {
      return this.props.width || 50;
  }
  
  getHeight() {
      return this.props.height || 50;
  }
    
  render() {
      
      var width = this.getWidth(),
          height = this.getHeight();
    return <div >
            <div>
                <a className="btn" onClick={(e)=>{ this.clear(); }}>Очистить</a>
                <a className="btn" onClick={(e)=>{ this.save(); }}>Сохранить</a>
            </div>
            <canvas id={this.idconvas}
                    height={height} 
                    width={width} 
                    ref={(canvas) => {this.canvas = canvas}}
                />
           </div>;
  }
}

class DraftsmanImage extends React.Component {
    
    
    constructor(props) {
        super(props);
        this.state = { 
            url: this.getUrl()
        };
        
        const self = this;
        
        app.addObserver('Draftsman.update', (e) => {
             if(e.contextData.id == self.props.image.id) {
                 //TODO update data
                 self.setState({
                    url: this.getUrl() + '&t=' + Math.random()
                });   
             }
        });
        
    }
    
    getUrl() {
        return "index.php?r=api/image&id=" + this.props.image.id;
    }
    
    onClickSelect(e) { 
        e.stopPropagation();
        app.fire( new AppEvent('DraftsmanImage.select', this.props.image ));
        
        return false;
    }
    
    
    onClickDelete(e) {
        e.stopPropagation();
        if(confirm("Удалить?")) {
            app.fire( new AppEvent('DraftsmanImage.delete', this.props.image ));
        } 
        return false;
        
    }
    
    render() {
        return <div className="highlight" >ID: {this.props.image.id} 
                <a className="btn" href="#" onClick={this.onClickSelect.bind(this)}>Изменить</a>
                <a className="btn" href="#" onClick={this.onClickDelete.bind(this)}>Удалить</a>
                <img className="img-thumbnail" width="50"  height="50" src={this.state.url}/></div>
    }
}


class DraftsmanImageList extends React.Component  {
    
    constructor(props) {
        super(props);
        this.state = {
            list: [   ]
        };
        
        const self = this;
        
        app.addObserver('App.loadimages', (e) => {
            
            self.setState({
                list: e.contextData,
            });
        })
    }
    
    render() {
        
        
        const dataStr = (this.state.list) ? this.state.list.map((i) => {
                 return  <li key={i.id}><DraftsmanImage image={i} /></li>;
             }) : ''; 
        
        return <ul>{dataStr}</ul>;
    }
}



 
app.init(); 