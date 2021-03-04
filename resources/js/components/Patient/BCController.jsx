import React from 'react';
import axios from "axios";
import PagesTitle from "../props/utils/PagesTitle";



class BCBase extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            add: false,
        }
    }


    render() {
        return (
         <div className="BC-base">
             <section className="header">
                 <PagesTitle title={'Black Codes'}/>
                 <button className={'btn'} onClick={()=>this.setState({add: true})}>Ajouter un BC</button>
             </section>
             <section className="contain">
                 <div className="BC-List">
                     <h1>En cours</h1>
                     <div  className="card">
                         <h3>Fusiallade</h3>
                         <h4>Los santos long beach</h4>
                         <div className="separator"/>
                         <div className={'rowed'}>
                             <h5>Secouristes : </h5>
                             <h5>7</h5>
                         </div>
                         <div className={'rowed'}>
                             <h5>Victimes : </h5>
                             <h5>12</h5>
                         </div>
                         <div className="separator"/>
                         <h4>00/00/0000 à 00h00</h4>
                         <h4>alerte de Jean Claude Bernard</h4>
                     </div>
                     <div className="card">
                         <h3>Fusiallade</h3>
                         <h4>Los santos long beach</h4>
                         <div className="separator"/>
                         <div className={'rowed'}>
                             <h5>Secouristes : </h5>
                             <h5>7</h5>
                         </div>
                         <div className={'rowed'}>
                             <h5>Victimes : </h5>
                             <h5>12</h5>
                         </div>
                         <div className="separator"/>
                         <h4>00/00/0000 à 00h00</h4>
                         <h4>alerte de Jean Claude Bernard</h4>
                     </div>
                     <div className="card">
                         <h3>Fusiallade</h3>
                         <h4>Los santos long beach</h4>
                         <div className="separator"/>
                         <div className={'rowed'}>
                             <h5>Secouristes : </h5>
                             <h5>7</h5>
                         </div>
                         <div className={'rowed'}>
                             <h5>Victimes : </h5>
                             <h5>12</h5>
                         </div>
                         <div className="separator"/>
                         <h4>00/00/0000 à 00h00</h4>
                         <h4>alerte de Jean Claude Bernard</h4>
                     </div>
                     <div className="card">
                         <h3>Fusiallade</h3>
                         <h4>Los santos long beach</h4>
                         <div className="separator"/>
                         <div className={'rowed'}>
                             <h5>Secouristes : </h5>
                             <h5>7</h5>
                         </div>
                         <div className={'rowed'}>
                             <h5>Victimes : </h5>
                             <h5>12</h5>
                         </div>
                         <div className="separator"/>
                         <h4>00/00/0000 à 00h00</h4>
                         <h4>alerte de Jean Claude Bernard</h4>
                     </div>
                     <div className="card">
                         <h3>Fusiallade</h3>
                         <h4>Los santos long beach</h4>
                         <div className="separator"/>
                         <div className={'rowed'}>
                             <h5>Secouristes : </h5>
                             <h5>7</h5>
                         </div>
                         <div className={'rowed'}>
                             <h5>Victimes : </h5>
                             <h5>12</h5>
                         </div>
                         <div className="separator"/>
                         <h4>00/00/0000 à 00h00</h4>
                         <h4>alerte de Jean Claude Bernard</h4>
                     </div>
                 </div>
                 <div className="BC-List">
                     <h1>Anciens</h1>
                     <div className="card">
                         <h3>Fusiallade</h3>
                         <h4>Los santos long beach</h4>
                         <div className="separator"/>
                         <div className={'rowed'}>
                             <h5>Secouristes : </h5>
                             <h5>7</h5>
                         </div>
                         <div className={'rowed'}>
                             <h5>Victimes : </h5>
                             <h5>12</h5>
                         </div>
                         <div className="separator"/>
                         <h4>du 00/00/0000 à 00h00</h4>
                         <h4>au 00/00/0000 à 00h00</h4>
                         <h4>alerte de Jean Claude Bernard</h4>
                     </div>
                     <div className="card">
                         <h3>Fusiallade</h3>
                         <h4>Los santos long beach</h4>
                         <div className="separator"/>
                         <div className={'rowed'}>
                             <h5>Secouristes : </h5>
                             <h5>7</h5>
                         </div>
                         <div className={'rowed'}>
                             <h5>Victimes : </h5>
                             <h5>12</h5>
                         </div>
                         <div className="separator"/>
                         <h4>du 00/00/0000 à 00h00</h4>
                         <h4>au 00/00/0000 à 00h00</h4>
                         <h4>alerte de Jean Claude Bernard</h4>
                     </div>
                     <div className="card">
                         <h3>Fusiallade</h3>
                         <h4>Los santos long beach</h4>
                         <div className="separator"/>
                         <div className={'rowed'}>
                             <h5>Secouristes : </h5>
                             <h5>7</h5>
                         </div>
                         <div className={'rowed'}>
                             <h5>Victimes : </h5>
                             <h5>12</h5>
                         </div>
                         <div className="separator"/>
                         <h4>du 00/00/0000 à 00h00</h4>
                         <h4>au 00/00/0000 à 00h00</h4>
                         <h4>alerte de Jean Claude Bernard</h4>
                     </div>
                     <div className="card">
                         <h3>Fusiallade</h3>
                         <h4>Los santos long beach</h4>
                         <div className="separator"/>
                         <div className={'rowed'}>
                             <h5>Secouristes : </h5>
                             <h5>7</h5>
                         </div>
                         <div className={'rowed'}>
                             <h5>Victimes : </h5>
                             <h5>12</h5>
                         </div>
                         <div className="separator"/>
                         <h4>du 00/00/0000 à 00h00</h4>
                         <h4>au 00/00/0000 à 00h00</h4>
                         <h4>alerte de Jean Claude Bernard</h4>
                     </div>
                     <div className="card">
                         <h3>Fusiallade</h3>
                         <h4>Los santos long beach</h4>
                         <div className="separator"/>
                         <div className={'rowed'}>
                             <h5>Secouristes : </h5>
                             <h5>7</h5>
                         </div>
                         <div className={'rowed'}>
                             <h5>Victimes : </h5>
                             <h5>12</h5>
                         </div>
                         <div className="separator"/>
                         <h4>du 00/00/0000 à 00h00</h4>
                         <h4>au 00/00/0000 à 00h00</h4>
                         <h4>alerte de Jean Claude Bernard</h4>
                     </div>
                     <div className="card">
                         <h3>Fusiallade</h3>
                         <h4>Los santos long beach</h4>
                         <div className="separator"/>
                         <div className={'rowed'}>
                             <h5>Secouristes : </h5>
                             <h5>7</h5>
                         </div>
                         <div className={'rowed'}>
                             <h5>Victimes : </h5>
                             <h5>12</h5>
                         </div>
                         <div className="separator"/>
                         <h4>du 00/00/0000 à 00h00</h4>
                         <h4>au 00/00/0000 à 00h00</h4>
                         <h4>alerte de Jean Claude Bernard</h4>
                     </div>
                     <div className="card">
                         <h3>Fusiallade</h3>
                         <h4>Los santos long beach</h4>
                         <div className="separator"/>
                         <div className={'rowed'}>
                             <h5>Secouristes : </h5>
                             <h5>7</h5>
                         </div>
                         <div className={'rowed'}>
                             <h5>Victimes : </h5>
                             <h5>12</h5>
                         </div>
                         <div className="separator"/>
                         <h4>du 00/00/0000 à 00h00</h4>
                         <h4>au 00/00/0000 à 00h00</h4>
                         <h4>alerte de Jean Claude Bernard</h4>
                     </div>

                 </div>
             </section>
             {this.state.add &&
                 <section className={'popup'}>
                     <div className={'popup-content'}>
                        <h1>Ajouter un BC</h1>
                        <form>
                            <div className={'row'}>
                                <input type={'text'} placeholder={'lieux'}/>
                                <select defaultValue={1}>
                                    <option value={1} disabled>add</option>
                                </select>
                            </div>
                            <div className={'btn-contain'}>
                                <button onClick={()=> this.setState({add: false})} className={'btn'}>fermer</button>
                                <button type={'submit'} className={'btn'}>Ajouter</button>
                            </div>

                        </form>
                     </div>
                 </section>
             }
         </div>
        )
    }
}

class BCLast extends React.Component {
    render() {
        return null;
    }
}

class BCView extends React.Component {
    render() {
        return null
    }
}

class BCController extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            status: 0
        }
    }

    render() {
        return (
            <div className={"BC-Container"}>
                {this.state.status === 0 &&
                    <BCBase/>
                }
                {this.state.status === 1 &&
                    <BCView/>
                }
                {this.state.status === 3 &&
                    <BCLast/>
                }
                {this.state.status === null &&
                    <div className={'load'}>
                        <img src={'/assets/images/loading.svg'} alt={''}/>
                    </div>
                }
            </div>
        )
    }
}

export default BCController;
