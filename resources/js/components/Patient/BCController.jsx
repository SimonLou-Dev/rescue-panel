import React from 'react';
import axios from "axios";
import PagesTitle from "../props/utils/PagesTitle";
import PatientListPU from "../props/Patient/Urgence/PatientListPU";
import dateFormat from "dateformat";
import PermsContext from "../context/PermsContext";



class ListPatient extends React.Component {
    constructor(props) {
        super(props);
        this.state= {
            patients: this.props.patients,
        }
    }

    componentDidUpdate(prevProps, prevState, snapshot) {
        if(this.props.patients !== prevState.patients) {
            this.setState({patients: this.props.patients})
        }
    }

    render() {
        return (
            <section className="list-container" style={{filter: this.props.blur? 'blur(5px)' : 'none'}}>
                <div className={'list-content'}>
                    <h1>Liste des patients ({this.state.patients ? this.state.patients.length : '?'})</h1>
                    <div className={'list'}>
                        {this.state.patients !== null && this.state.patients.map((patient)=>
                            <PatientListPU name={patient.name} date={dateFormat(patient.created_at, 'hh:mm')} urlid={patient.rapport_id} color={patient.get_color.name} idcard={patient.idcard}/>
                        )}
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
            clicked:false,
            place: "",
            type: 0,
            errors:[],
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
        if(this.state.type !== 0){
            await axios({
                method: 'POST',
                url: '/data/blackcode/create',
                data: {
                    type:this.state.type,
                    place:this.state.place,
                }
            }).then(response => {
                this.setState({place: "", type:0})
                this.props.update(1,response.data.bc_id);
            }).catch(error => {
                error = Object.assign({}, error);
                if(error.response.status === 422){
                    this.setState({errors: error.response.data.errors})
                }
            })
        }
        this.setState({cliked:false});
    }
    render() {
        var perm = this.context;
        return (
         <div className="BC-base">
             <section className="header" style={{filter: this.state.add ? 'blur(5px)' : 'none'}}>
                 <PagesTitle title={'Black Codes'}/>
                 {perm.add_BC === 1 &&
                    <button className={'btn'} onClick={()=>this.setState({add: true})}>Ajouter un BC</button>
                 }
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
                            <div className="card" onClick={async () => {
                                var req = await axios({
                                    method: 'post',
                                    url: '/data/blackcode/' + bc.id + '/add/personnel',
                                })
                                if (req.status === 201) {
                                    this.props.update(1, bc.id)
                                }
                            }}>
                                <h3>{bc.get_type.name} #{bc.id}</h3>
                                <h4>{bc.place}</h4>
                                <div className="separator"/>
                                <div className={'rowed'}>
                                    <h5>Secouristes : </h5>
                                    <h5>{bc.secouristes}</h5>
                                </div>
                                <div className={'rowed'}>
                                    <h5>Victimes : </h5>
                                    <h5>{bc.patients}</h5>
                                </div>
                                <div className="separator"/>
                                <h4>{dateFormat(bc.created_at, 'yyyy/mm/dd H:MM')} [FR]</h4>
                                <h4>alerte de {bc.get_user.name}</h4>
                            </div>
                        )
                     }
                 </div>
                 <div className="BC-List">
                     <h1>Anciens</h1>
                     {!this.state.data &&
                     <div className={'load'}>
                         <img src={'/assets/images/loading.svg'} alt={''}/>
                     </div>
                     }
                     {this.state.ended &&
                     this.state.ended.map((bc) =>
                         <div className="card" onClick={()=>{
                             this.props.update(2,bc.id)
                         }}>
                             <h3>{bc.get_type.name} #{bc.id}</h3>
                             <h4>{bc.place}</h4>
                             <div className="separator"/>
                             <div className={'rowed'}>
                                 <h5>Secouristes : </h5>
                                 <h5>{bc.secouristes}</h5>
                             </div>
                             <div className={'rowed'}>
                                 <h5>Victimes : </h5>
                                 <h5>{bc.patients}</h5>
                             </div>
                             <div className="separator"/>
                             <h4>{dateFormat(bc.created_at, 'yyyy/mm/dd H:MM')} [FR]</h4>
                             <h4>{dateFormat(bc.updated_at, 'yyyy/mm/dd H:MM')} [FR]</h4>
                             <h4>alerte de {bc.get_user.name}</h4>
                         </div>
                     )}
                 </div>
             </section>
             {this.state.add &&
                 <section className={'popup'}>
                     <div className={'popup-content'}>
                        <h1>Ajouter un BC</h1>
                        <form onSubmit={(e)=>{
                            e.preventDefault();
                            this.addbc(e)
                        }}>
                            <div className={'row'}>
                                <input type={'text'} placeholder={'lieux'} className={(this.state.errors.place ? 'form-error': '')} value={this.state.place} onChange={(e)=>{this.setState({place:e.target.value})}}/>
                                <ul className={'error-list'}>
                                    {this.state.errors.place && this.state.errors.place.map((item)=>
                                        <li>{item}</li>
                                    )}
                                </ul>
                                <select defaultValue={this.state.type} onChange={(e)=>{this.setState({type:e.target.value})}}>
                                    <option value={0} disabled>choisir</option>
                                    {this.state.types && this.state.types.map((type)=>
                                        <option key={type.id} value={type.id}>{type.name}</option>
                                    )}
                                </select>
                            </div>
                            <div className={'btn-contain'}>
                                <button onClick={()=> this.setState({add: false})} className={'btn'}>fermer</button>
                                <button type={'submit'} disabled={this.state.clicked===true} className={'btn'} onClick={()=>{this.setState({clicked:true});}}>Ajouter</button>
                            </div>

                        </form>
                     </div>
                 </section>
             }
         </div>
        )
    }
}
BCBase.contextType = PermsContext;

class BCLast extends React.Component {

    constructor(props) {
        super(props);
        this.state = {
            bc: [],
            data:false,
        }
    }

    async componentDidMount() {
        var req = await axios({
            url: '/data/blackcode/' + this.props.id + '/infos',
            method: 'GET'
        })
        if(req.status === 200){
            this.setState({data:true, bc:req.data.bc})
        }
    }

    render() {
        if(this.state.data) {
            const bc = this.state.bc;
            return (
                <div className={"BC-Last"}>
                    <section className="left">
                        <div className={'header'}>
                            <PagesTitle title={bc.get_type.name + ' ' + bc.place}/>
                            <div className={'btn-contain'}>
                                <div className={'bgforbtn'}>
                                    <button className={'btn'} onClick={() => this.props.update(0)}>Retour</button>
                                </div>
                            </div>
                        </div>
                        <div className="infos">
                            <h2>Informations</h2>
                            <div className={'row-spaced'}>
                                <label>date de début</label>
                                <label>{dateFormat(bc.created_at, 'yyyy/mm/dd H:MM')} [FR]</label>
                            </div>
                            <div className={'row-spaced'}>
                                <label>date de fin</label>
                                <label>{dateFormat(bc.updated_at, 'yyyy/mm/dd H:MM')} [FR]</label>
                            </div>
                            <div className={'row-spaced'}>
                                <label>Patients secourus</label>
                                <label>{bc.get_patients.length}</label>
                            </div>
                            <div className={'row-spaced'}>
                                <label>Personnel engagé</label>
                                <label>{bc.get_personnel.length}</label>
                            </div>
                            <div className={'row-spaced'}>
                                <label>Bc engagé par</label>
                                <label>{bc.get_user.name}</label>
                            </div>
                        </div>
                        <div className="personnel-list">
                            {bc.get_personnel.map((user) =>
                                <div className="tag">{user.name}</div>
                            )}
                        </div>
                    </section>
                    <ListPatient patients={this.state.bc.get_patients}/>
                </div>
            );
        }else{
            return (
                <div className={'load'}>
                    <img src={'/assets/images/loading.svg'} alt={''}/>
                </div>
            )
        }

    }
}

class BCView extends React.Component {

    constructor(props) {
        super(props);
        this.state = {
            CloseMenuOpen:false,
            id:this.props.id,
            data:null,
            patients:null,
            bc:null,
            personnels:null,
            blessures:null,
            couleurs:null,
            nom:"",
            color:0,
            blessure:0,
            payed:false,
            carteid:false,
            searsh:null,
            clicked:false,
            correctid: true,
            realname: '',
            errors: []
        }
        this.quitbc = this.quitbc.bind(this);
        this.check = this.check.bind(this);
        this.update = this.update.bind(this);
        this.searsh = this.searsh.bind(this);
        this.post = this.post.bind(this);
    }

    async quitbc() {
        var req = await axios({
            method: 'delete',
            url: '/data/blackcode/' + this.props.id + '/delete/personnel',
        })
        if (req.status === 202) {
            this.props.update(0);
        }
    }

    componentDidUpdate(prevProps, prevState, snapshot) {
        if(prevProps.id !==this.props.id){
            this.setState({id:this.props.id})
            this.update();
        }
    }

    async update(){
        let req = await axios({
            method: 'GET',
            url: '/data/blackcode/' + this.props.id + '/infos',
        });
        if(req.status === 200){
            this.setState({
                data: true,
                bc: req.data.bc,
                patients: req.data.bc.get_patients,
                personnels: req.data.bc.get_personnel,
                blessures: req.data.blessures,
                couleurs: req.data.colors,
            })
        }
    }

    async check(){
        let req = await axios({
            method: 'GET',
            url: '/data/blackcode/' + this.props.id + '/status',
        });
        if(req.status === 200 && req.data.ended){
            this.props.update(0)
        }
    }

    async searsh(nom){
        this.setState({ nom:nom});
        var len =  nom.length;
        if(len > 3){
            let req = await axios({
                method: 'GET',
                url: '/data/patient/search/' + nom,
            });
            if(req.status === 200){
                this.setState({searsh: req.data.list})
            }
        }


    }

    async post(e){
        this.setState({clicked:true})
        e.preventDefault()
        if(this.state.blessure !== 0 && this.state.color !== 0){
            await axios({
                url: '/data/blackcode/'+ this.props.id +'/add/patient',
                method: 'post',
                data: {
                    name: this.state.nom,
                    color: this.state.color,
                    blessure: this.state.blessure,
                    payed: this.state.payed,
                    carteid: this.state.carteid,
                    correctid: this.state.correctid,
                    realname: this.state.realname,

                }
            }).then(response => {
                if(response.status === 201){
                    this.setState({
                        nom: '',
                        color: 0,
                        blessure:0,
                        payed: false,
                        carteid: false,
                        correctid: true,
                        realname: '',
                    });
                }
            }).catch(error => {
                error = Object.assign({}, error);
                if(error.response.status === 422){
                    this.setState({errors: error.response.data.errors})
                }
            })

        }
        this.setState({clicked:false})
    }

    componentDidMount() {

        this.update();
        this.check();

        this.updator =setInterval(
            () =>this.update(),
            20000
        );
        this.checker =setInterval(
            () =>this.check(),
            10000
        );
    }

    componentWillUnmount() {
        clearInterval(this.updator);
        clearInterval(this.checker);
    }

    render() {
        return (
            <div className={"BC-View"}>
                <section style={{filter: this.state.CloseMenuOpen ? 'blur(5px)' : 'none'}} className="left">
                    <div className={'header'}>
                        {this.state.data &&
                        <PagesTitle title={this.state.bc.get_type.name + ' ' + this.state.bc.place}/>
                        }
                        <div className={'btn-contain'}>
                            <div className={'bgforquibtn'}>
                                <button className={'btn'} onClick={this.quitbc}>Quitter le BC</button>
                            </div>
                            <div className={'bgforbtn'}>
                                <button className={'btn'} onClick={()=>this.setState({CloseMenuOpen: true})}>Fermer le BC</button>
                            </div>
                        </div>
                    </div>
                    <div className="addpatient">

                        <form onSubmit={(e)=>{
                            this.post(e);
                        }}>
                            <div className="top">
                                <button type={"submit"} className={'btn'} disabled={this.state.clicked === true}>ajouter</button>
                                <h2>Ajouter un patient</h2>
                            </div>

                            <div className={'row-spaced'}>
                                <label>prénom nom :</label>
                                <input list="autocomplete" autoComplete="off" className={'input '+ (this.state.errors.name ? 'form-error': '')} type={'text'} value={this.state.nom} onChange={(e)=>{this.searsh(e.target.value)}}/>
                                <datalist id="autocomplete">
                                    {this.state.searsh && this.state.searsh.map((patient)=>
                                        <option key={patient.id} value={patient.vorname+ ' '+patient.name}/>
                                    )}
                                </datalist>
                                {this.state.errors.name &&
                                <ul className={'error-list'}>
                                    {this.state.errors.name.map((item)=>
                                        <li>{item}</li>
                                    )}
                                </ul>
                                }
                            </div>

                            <div className={'row-spaced'}>
                                <label>Couleur dominante :</label>
                                <select className={'input'} value={this.state.color} onChange={(e)=>{this.setState({color:e.target.value})}} >
                                    <option value={0} disabled>choisir</option>
                                    {this.state.data && this.state.couleurs.map((item)=>
                                        <option key={item.id} value={item.id}>{item.name}</option>
                                    )}
                                </select>
                            </div>
                            <div className={'row-spaced'}>
                                <label>Type de blessure :</label>
                                <select className={'input'} value={this.state.blessure} onChange={(e)=>{this.setState({blessure:e.target.value})}}>
                                    <option value={0} disabled>choisir</option>
                                    {this.state.data && this.state.blessures.map((item)=>
                                        <option key={item.id} value={item.id}>{item.name}</option>
                                    )}
                                </select>
                            </div>
                           <div className={'idCheck'}>

                               <div className={'row-spaced'}>
                                   <label>HRP identité correcte:</label>
                                   <div className={'switch-container'}>
                                       <input id={"switch"+12} className={"payed_switch "+ (this.state.errors.correctid ? 'form-error': '')} type="checkbox" checked={this.state.correctid} onChange={(e)=>{
                                           if(this.state.correctid){
                                               this.setState({correctid:false})
                                           }else{
                                               this.setState({correctid:true})
                                           }
                                       }}/>
                                       <label htmlFor={"switch"+12} className={"payed_switchLabel"}/>
                                   </div>
                               </div>
                               {this.state.correctid === false &&
                               <div className={'row-spaced'}>
                                   <label>HRP nom prénom  : </label>
                                   <input autoComplete="off" placeholder={'prénom nom réels'} className={'input'} type={'text'} value={this.state.realname} onChange={(e)=>{this.setState({realname:e.target.value})}}/>
                                   {this.state.errors.realname &&
                                   <ul className={'error-list'}>
                                       {this.state.errors.realname.map((item)=>
                                           <li>{item}</li>
                                       )}
                                   </ul>
                                   }
                               </div>
                               }
                           </div>
                            <div className={'bottom'}>
                                <div className="paye">
                                    <label>Payé : </label>
                                    <div className={'switch-container'}>
                                        <input id={"switch"+1} className="payed_switch" type="checkbox" checked={this.state.payed} onChange={(e)=>{
                                            if(this.state.payed){
                                                this.setState({payed:false})
                                            }else{
                                                this.setState({payed:true})
                                            }
                                        }}/>
                                        <label htmlFor={"switch"+1} className={"payed_switchLabel"}/>
                                    </div>
                                </div>
                                <div className="idcard">
                                    <label>carte d'identité : </label>
                                    <div className="onoffswitch">
                                        <input type="checkbox" name="onoffswitch" className="onoffswitch-checkbox"
                                               id="myonoffswitch" tabIndex="0" checked={this.state.carteid} onChange={(e)=>{
                                                if(this.state.carteid){
                                                    this.setState({carteid:false})
                                                }else{
                                                    this.setState({carteid:true})
                                                }
                                               }}/>
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
                        {!this.state.data &&
                        <div className={'load'}>
                            <img src={'/assets/images/loading.svg'} alt={''}/>
                        </div>
                        }
                        {this.state.data &&
                            this.state.personnels.map((user)=>
                                <div className="tag">{user.name}</div>
                            )
                        }
                    </div>
                </section>
                <ListPatient blur={this.state.CloseMenuOpen} patients={this.state.patients}/>
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
                                if(req.status === 201){
                                    this.props.update(0);
                                }
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
            status: null,
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
                    <BCLast id={this.state.bc_id} update={(status, id)=> {this.updatestatus(status, id)}}/>
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
