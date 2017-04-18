 
 
 
class AppEvent {
    
    
    
    constructor(type, contextData = null) {
        this.type = type;
        this.contextData = contextData;
    }
    
} 
 
class App {
    
    
    constructor() {
        this.addObserver('Draftsman.save', (e) => {
            console.log(e.contextData, this.imageDataToString(e.contextData));//TODO send to server
            
            var dataMono = {};
        });
        
        this.addObserver('Draftsman.init', (e) => { 
            let s = '640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.288.11.342.286.17.338.284.19.338.284.21.336.284.21.336.284.21.336.284.19.338.286.17.338.288.13.340.292.3.346.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640.640';
            
             let idd = this.stringToImageData(s, 320, 320);
            console.log(idd);
            e.contextData.getConvas2D().putImageData(idd, 0, 0, 0, 0, 320, 320);
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
    
    
    stringToImageData( data, width, height ) {
        let len = width * height * 4, dataImage = new Uint8ClampedArray(len), 
            spl = data.split('.'), k, vl,bit,cbit,i, index = 0;
        for(k in spl) {
            vl = spl[k];
            bit = vl & 1;
            cbit = vl - bit >> 1;
           
            for(i = 0; i < cbit; i ++) {
               dataImage[index ++] =  bit * 255;
               dataImage[index ++] =  bit * 255;
               dataImage[index ++] =  bit * 255;
               dataImage[index ++] =  255;
            }
        }
        
        return new ImageData(dataImage, width, height);
    }
    
    imageDataToString(imageData) {
        let height = imageData.height,
            width = imageData.width,
            data = imageData.data,
            result = [],
            i, y=0, x=0, r, g, b, bit, lbit = -1, cbit,len =data.length;
      
        for (i = 0; i < len; i += 4) {
            
            r = data[((width * y) + x) * 4];
            g = data[((width * y) + x) * 4 + 1];
            b = data[((width * y) + x) * 4 + 2]; 
            bit = (r  > 127 || g  > 127 || b  > 127) ? 1 : 0;
            
            if(bit == lbit) {
                cbit ++;
            } else {
                if(lbit != -1) {
                    result.push( cbit << 1 | lbit);
                }
                lbit = bit;
                cbit = 1;
            } 
            
            x ++;
            if(x >= width) {
                x = 0;
                y ++;
                
                if(cbit > 0 && lbit != -1) {
                    result.push( cbit << 1 | lbit);
                }
                lbit = -1;
                cbit= 0;
            }
        }
        
        return result.join('.');
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
    this.state = {
        
    };
     
    this.beginDraw = false;
    this.idconvas = 'canvas-me';
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
      cn.fillStyle="#ffffff";
      cn.arc(x,y, this.getBrushSize(),0,Math.PI*2,true);
      cn.closePath();
      cn.fill();
  }
  
  getConvas() {
      return document.getElementById( this.idconvas );
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
      console.log('componentDidMount', this);
      let canvas = document.getElementById( this.idconvas );
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
                <a className="btn" onClick={(e)=>{ this.clear(); }}>clear</a>
                <a className="btn" onClick={(e)=>{ this.save(); }}>save</a>
            </div>
            <canvas id={this.idconvas}
                    height={height} 
                    width={width} 
                />
           </div>;
  }
}



ReactDOM.render(<Draftsman width="320" height="320" />, document.getElementById('draw')); 

 
 