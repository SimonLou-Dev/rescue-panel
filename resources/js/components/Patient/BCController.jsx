import React from 'react';
import axios from "axios";
import PagesTitle from "../props/utils/PagesTitle";
import PatientListPU from "../props/Patient/Urgence/PatientListPU";



class ListPatient extends React.Component {
    render() {
        return (
            <section className="list-container">
                <div className={'list-content'}>
                    <h1>Liste des patients</h1>
                    <div className={'list'}>
                        <PatientListPU name={'Simon Lou'} date={'16h00'} urlid={1} color={'Pas de couleur dominate'} idcard={true}/>
                        <PatientListPU name={'Simon Lou Lou'} date={'16h00'} urlid={1} color={'Pas de couleur dominate'}/>
                        <PatientListPU name={'Kendrick Anderson'} date={'16h00'} urlid={1} color={'Pas de couleur dominate'}/>
                        <PatientListPU name={'Galaverraga Arturo'} date={'16h00'} urlid={1} color={'Pas de couleur dominate'}/>
                        <PatientListPU name={'Pavoh Sam'} date={'16h00'} urlid={1} color={'Pas de couleur dominate'}/>
                        <PatientListPU name={'test'} date={'16h00'} urlid={1} color={'Pas de couleur dominate'}/>
                        <PatientListPU name={'test'} date={'16h00'} urlid={1} color={'Pas de couleur dominate'}/>
                        <PatientListPU name={'test'} date={'16h00'} urlid={1} color={'Pas de couleur dominate'}/>
                        <PatientListPU name={'test'} date={'16h00'} urlid={1} color={'Pas de couleur dominate'}/>
                        <PatientListPU name={'test'} date={'16h00'} urlid={1} color={'Pas de couleur dominate'}/>
                        <PatientListPU name={'test'} date={'16h00'} urlid={1} color={'Pas de couleur dominate'}/>
                        <PatientListPU name={'test'} date={'16h00'} urlid={1} color={'Pas de couleur dominate'}/>
                        <PatientListPU name={'test'} date={'16h00'} urlid={1} color={'Pas de couleur dominate'}/>
                        <PatientListPU name={'test'} date={'16h00'} urlid={1} color={'Pas de couleur dominate'}/>
                        <PatientListPU name={'test'} date={'16h00'} urlid={1} color={'Pas de couleur dominate'}/>
                        <PatientListPU name={'test'} date={'16h00'} urlid={1} color={'Pas de couleur dominate'}/>
                        <PatientListPU name={'test'} date={'16h00'} urlid={1} color={'Pas de couleur dominate'}/><PatientListPU name={'test'} date={'16h00'} urlid={1} color={'Pas de couleur dominate'}/>
                        <PatientListPU name={'test'} date={'16h00'} urlid={1} color={'Pas de couleur dominate'}/>
                        <PatientListPU name={'test'} date={'16h00'} urlid={1} color={'Pas de couleur dominate'}/>
                        <PatientListPU name={'test'} date={'16h00'} urlid={1} color={'Pas de couleur dominate'}/>
                        <PatientListPU name={'test'} date={'16h00'} urlid={1} color={'Pas de couleur dominate'}/>

                    </div>
                </div>
            </section>
        )
    }
}

class BCBase extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            add: false,
            active : undefined,
            types: undefined,
            ended: undefined,
            data: false,
            place: "",
            type: 0,
        }
        this.addbc = this.addbc.bind(this);
    }

    async componentDidMount() {
        var req = await axios({
            method: 'GET',
            url: '/data/blackcode/load',
        })
        if (req.status === 200){
            this.setState({active: req.data.active, ended: req.data.ended, types: req.data.types, data: true})
        }
    }

    async addbc(e) {
        e.preventDefault();
        var req = await axios({
            method: 'POST',
            url: '/data/blackcode/create',
            data: {
                type: this.state.type,
                place: this.state.place,
            }
        })
        if(req.status === 201){
            this.setState({place: "", type:0})
            this.props.update(1,req.data.bc_id);
        }
    }


    render() {
        return (
         <div className="BC-base">
             <section className="header" style={{filter: this.state.add ? 'blur(5px)' : 'none'}}>
                 <PagesTitle title={'Black Codes'}/>
                 <button className={'btn'} onClick={()=>this.setState({add: true})}>Ajouter un BC</button>
             </section>
             <section className="contain" style={{filter: this.state.add ? 'blur(5px)' : 'none'}} >
                 <div className="BC-List">
                     <h1>En cours</h1>
                     {!this.state.data &&
                     <div className={'load'}>
                         <img src={'/assets/images/loading.svg'} alt={''}/>
                     </div>
                     }
                     {this.state.active &&
                        this.state.active.map((bc) =>
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
                        )
                     }

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
                     {!this.state.data &&
                     <div className={'load'}>
                         <img src={'/assets/images/loading.svg'} alt={''}/>
                     </div>
                     }
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
                        <form onSubmit={(e)=>{this.addbc(e)}}>
                            <div className={'row'}>
                                <input type={'text'} placeholder={'lieux'} value={this.state.place} onChange={(e)=>{this.setState({place:e.target.value})}}/>
                                <select defaultValue={this.state.type} onChange={(e)=>{this.setState({type:e.target.value})}}>
                                    <option value={0} disabled>choisir</option>
                                    {this.state.types && this.state.types.map((type)=>
                                        <option key={type.id} value={type.id}>{type.name}</option>
                                    )}
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
        return (
            <div className={"BC-Last"}>
                <section className="left">
                    <div className={'header'}>
                        <PagesTitle title={'Fusillade LS Longs beach'}/>
                    </div>
                    <div className="infos">
                            <h2>Informations</h2>
                            <div className={'row-spaced'}>
                                <label>date de début</label>
                                <label>00/00/0000 à 00h00</label>
                            </div>
                            <div className={'row-spaced'}>
                                <label>date de fin</label>
                                <label>00/00/0000 à 00h00</label>
                            </div>
                            <div className={'row-spaced'}>
                                <label>durée</label>
                                <label>00h00</label>
                            </div>
                            <div className={'row-spaced'}>
                                <label>Patients secourus</label>
                                <label>10</label>
                            </div>
                            <div className={'row-spaced'}>
                                <label>Personnel engagé</label>
                                <label>3</label>
                            </div>
                            <div className={'row-spaced'}>
                                <label>Bc engagé par</label>
                                <label>Jean mouloud</label>
                            </div>
                    </div>
                    <div className="personnel-list">
                        <div className="tag">Lorem Ispum</div>
                        <div className="tag">Lorem Ispum Dolor</div>
                        <div className="tag">Lorem Ispum</div>
                        <div className="tag">Lorem Ispum Dolor</div>
                        <div className="tag">Lorem Ispum</div>
                        <div className="tag">Lorem Ispum Dolor</div>
                        <div className="tag">Lorem Ispum </div>
                        <div className="tag">Lorem Ispum Dolor</div>
                        <div className="tag">Lorem Ispum Dolor</div>
                        <div className="tag">Lorem Ispum Dolor</div>
                        <div className="tag">Lorem Ispum Dolor</div>
                        <div className="tag">Lorem Ispum Dolor</div>
                        <div className="tag">Lorem Ispum Dolor</div>
                        <div className="tag">Lorem Ispum Dolor</div>
                        <div className="tag">Lorem Ispum Dolor</div>
                        <div className="tag">Lorem Ispum Dolor</div>
                        <div className="tag">Lorem Ispum Dolor</div>
                        <div className="tag">Lorem Ispum Dolor</div>
                        <div className="tag">Lorem Ispum Dolor</div>
                        <div className="tag">Lorem Ispum Dolor</div>
                        <div className="tag">Lorem Ispum Dolor</div>
                        <div className="tag">Lorem Ispum Dolor</div>
                        <div className="tag">Lorem Ispum Dolor</div>
                        <div className="tag">Lorem Ispum Dolor</div>
                        <div className="tag">Lorem Ispum Dolor</div>
                        <div className="tag">Lorem Ispum Dolor</div>
                        <div className="tag">Lorem Ispum Dolor</div>
                        <div className="tag">Lorem Ispum Dolor</div>
                        <div className="tag">Lorem Ispum Dolor</div>
                        <div className="tag">Lorem Ispum Dolor</div>
                    </div>
                </section>
                <ListPatient/>
            </div>
        );
    }
}

class BCView extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            CloseMenuOpen: false,
        }
    }

    render() {
        return (
            <div className={"BC-View"}>
                <section style={{filter: this.state.CloseMenuOpen ? 'blur(5px)' : 'none'}} className="left">
                    <div className={'header'}>
                        <PagesTitle title={'Fusillade LS Longs beach'}/>
                        <div className={'bgforbtn'}>
                            <button className={'btn'} onClick={()=>this.setState({CloseMenuOpen: true})}>Fermer le BC</button>
                        </div>
                    </div>
                    <div className="addpatient">
                        <form>
                            <div className="top">
                                <button type={"submit"} className={'btn'}>ajouter</button>
                                <h2>Ajouter un patient</h2>
                            </div>

                            <div className={'row-spaced'}>
                                <label>nom :</label>
                                <input className={'input'} type={'text'}/>
                            </div>
                            <div className={'row-spaced'}>
                                <label>prénom :</label>
                                <input className={'input'} type={'text'}/>
                            </div>
                            <div className={'row-spaced'}>
                                <label>Couleur dominante :</label>
                                <select className={'input'} defaultValue={1}>
                                    <option value={1} disabled>choisir</option>
                                    <option value={2}>test</option>
                                </select>
                            </div>
                            <div className={'row-spaced'}>
                                <label>Type de blessure :</label>
                                <select className={'input'} defaultValue={1}>
                                    <option value={1} disabled>choisir</option>
                                    <option value={2}>test</option>
                                </select>
                            </div>
                            <div className={'bottom'}>
                                <div className="paye">
                                    <label>Payé : </label>
                                    <div className={'switch-container'}>
                                        <input id={"switch"+1} className="payed_switch" type="checkbox"/>
                                        <label htmlFor={"switch"+1} className={"payed_switchLabel"}/>
                                    </div>
                                </div>
                                <div className="idcard">
                                    <label>carte d'identité :</label>
                                    <div className="onoffswitch">
                                        <input type="checkbox" name="onoffswitch" className="onoffswitch-checkbox"
                                               id="myonoffswitch" tabIndex="0"/>
                                            <label className="onoffswitch-label" htmlFor="myonoffswitch">
                                                <span className="onoffswitch-inner"/>
                                                <span className="onoffswitch-switch"/>
                                            </label>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div className="personnel-list">
                        <div className="tag">Lorem Ispum</div>
                        <div className="tag">Lorem Ispum Dolor</div>
                        <div className="tag">Lorem Ispum</div>
                        <div className="tag">Lorem Ispum Dolor</div>
                        <div className="tag">Lorem Ispum</div>
                        <div className="tag">Lorem Ispum Dolor</div>
                        <div className="tag">Lorem Ispum </div>
                        <div className="tag">Lorem Ispum Dolor</div>
                        <div className="tag">Lorem Ispum Dolor</div>
                        <div className="tag">Lorem Ispum Dolor</div>
                        <div className="tag">Lorem Ispum Dolor</div>
                        <div className="tag">Lorem Ispum Dolor</div>
                        <div className="tag">Lorem Ispum Dolor</div>
                        <div className="tag">Lorem Ispum Dolor</div>
                        <div className="tag">Lorem Ispum Dolor</div>
                        <div className="tag">Lorem Ispum Dolor</div>
                        <div className="tag">Lorem Ispum Dolor</div>
                        <div className="tag">Lorem Ispum Dolor</div>
                        <div className="tag">Lorem Ispum Dolor</div>
                        <div className="tag">Lorem Ispum Dolor</div>
                        <div className="tag">Lorem Ispum Dolor</div>
                        <div className="tag">Lorem Ispum Dolor</div>
                        <div className="tag">Lorem Ispum Dolor</div>
                        <div className="tag">Lorem Ispum Dolor</div>
                        <div className="tag">Lorem Ispum Dolor</div>
                        <div className="tag">Lorem Ispum Dolor</div>
                        <div className="tag">Lorem Ispum Dolor</div>
                        <div className="tag">Lorem Ispum Dolor</div>
                        <div className="tag">Lorem Ispum Dolor</div>
                        <div className="tag">Lorem Ispum Dolor</div>
                    </div>
                </section>
                <ListPatient style={{filter: this.state.CloseMenuOpen ? 'blur(5px)' : 'none'}} />
                {this.state.CloseMenuOpen &&
                <section className={'popup'}>
                    <div className={'popup-content'}>
                        <h1>Fermer le BC</h1>
                        <div className="close">
                            <button onClick={()=> this.setState({CloseMenuOpen: false})} className={'btn'}>annuler</button>
                            <button className={'btn'} onClick={async () => {
                                var req = await axios({
                                    method: 'PUT',
                                    url: '/data/blackcode/' + this.props.id + '/close',
                                })
                            }}>Oui</button>
                        </div>
                    </div>
                </section>
                }
            </div>
        )
    }
}

class BCController extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            status: 0,
            bc_id: undefined,
        }
        this.updatestatus = this.updatestatus.bind(this)
    }

    async componentDidMount() {
        var req = await axios({
            method: 'GET',
            url: '/data/blackcode/mystatus',
        });
        if(req.status === 200){
            if(req.data.bc !== null){
                this.updatestatus(1, req.data.bc)
            }
            else{
                this.updatestatus(0);
            }
        }else{
            this.updatestatus(0);
        }
    }

    updatestatus(status, id = undefined){
        console.log('update');
        this.setState({status: status});
        if(id !== undefined){
            this.setState({bc_id: id});
        }
    }
    render() {
        return (
            <div className={"BC-Container"}>
                {this.state.status === 0 &&
                    <BCBase update={(status, id)=> {this.updatestatus(status, id)}}/>
                }
                {this.state.status === 1 &&
                    <BCView id={this.state.bc_id} update={(status, id)=> {this.updatestatus(status, id)}}/>
                }
                {this.state.status === 2 &&
                    <BCLast update={this.updatestatus}/>
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
