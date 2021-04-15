import React from 'react';
import PagesTitle from "../props/utils/PagesTitle";
import axios from "axios";
import PermsContext from "../context/PermsContext";
class FormaUserList extends React.Component {

    constructor(props) {
        super(props);
        this.state = {
            data: false,
            formations: [],
            certifs: [],
            nbrForma: 0,
            arraybis: [],
        }
        this.updateCertif = this.updateCertif.bind(this)
    }

    async componentDidMount() {
        let req = await axios({
            url: '/data/certifications/admin/get',
            method: 'GET'
        })

        if (req.status === 200) {
            this.setState({
                data: true,
                formations: req.data.certifs
            })
        }

        //Création de l'array de chaque user
        let array = this.state.arraybis;
        let usernbr = 0;
        while (usernbr < req.data.users.length){
            const user = req.data.users[usernbr];
            const certifs = user.get_certifications;
            let formations = req.data.certifs;

            //On récupère les id des formations validés
            let validatedid = [];
            certifs.map((certif)=>{
                validatedid.push(certif.formation_id)
            })

            //formations array
            let allfomartions = [];

            //liste de toutes les formations
            formations.map((formation)=>{
                allfomartions.push({
                    id: formation.id,
                    validate: validatedid.includes(formation.id)
                })
            })

            //Obj de l'utilisateurs
            var obj = {
                name: user.name,
                id: user.id,
                formations: allfomartions
            }
            array.push(obj)
            usernbr++;
        }
        this.setState({arraybis:array})
    }

    async updateCertif(userid, formaid) {
        let array = this.state.arraybis;
        array.map((user) => {
            if (user.id === userid) {
                let formations = user.formations;
                formations.map((forma) => {
                    if (forma.id === formaid) {
                        forma.validate = !forma.validate
                    }
                })
            }
        })
        this.setState({arraybis: array});
        var req = await axios({
            url: '/data/certifications/admin/' + formaid + '/change/' + userid,
            method: 'PUT',
        })
    }

    render() {
        return (
            <div className="f-userlist">
                <section className="header">
                    <PagesTitle title={'Certifications des utilisateurs'}/>
                    <button onClick={()=>this.props.change(1)} className={'btn'}>Liste des formations</button>
                </section>
                <section className="user-list">
                    <table>
                        <thead>
                            <tr>
                                <th className={'name'}>nom</th>
                                {this.state.data && this.state.formations.map((formation)=>
                                    <th key={formation.id} className={'forma'}>{formation.name}</th>
                                )}
                            </tr>
                        </thead>
                        <tbody>
                        {this.state.data && this.state.arraybis && this.state.arraybis.map((user)=>
                            <tr key={user.id}>
                                <td className={'name'}>{user.name}</td>
                                {user.formations.map((forma)=>
                                    <td className={'forma'}>
                                        <div className={'pilote-btn'}>
                                                <input type="checkbox" id={"toggle_"+user.id+'_'+forma.id} checked={forma.validate === true} onClick={()=>{this.updateCertif(user.id, forma.id)}}/>
                                            <div>
                                                <label htmlFor={"toggle_"+user.id+'_'+forma.id} />
                                            </div>
                                        </div>
                                    </td>
                                )}
                            </tr>
                        )}
                        </tbody>
                    </table>
                </section>
            </div>
        );
    }
}

class FormaList extends React.Component {

    constructor(props) {
        super(props);
        this.state = {
            formations: [],
            data:false,
        }
    }

    async componentDidMount() {
        var req = await axios({
            url: '/data/formations/get',
            method: 'GET',
        })
        if(req.status === 200){
            this.setState({formations:req.data.formations, data:true})
        }
    }

    render() {
        let perm = this.context;
        return (
            <div className="f-formalist">
                <section className="header">
                    <PagesTitle title={'Liste des formations'}/>
                    <button onClick={()=>this.props.change(0)} className={'btn'}>Certifications</button>
                    <button disabled={!perm.create_forma} onClick={()=>{this.props.change(2)}} className={'btn'}>Creer une formation</button>
                </section>
                <section className={'f-list'}>
                    {this.state.data === true && this.state.formations.map((forma)=>
                        <div className={'item'}>
                            <div className={'columns img'}>
                                <img src={'/storage/formations/front_img/'+forma.id+ '/'+forma.image} alt={''}/>
                            </div>
                            <div className={'columns text'}>
                                <h2>{forma.name}</h2>
                                <p>{forma.desc}</p>
                            </div>
                            <div className={'columns infos'}>
                                <h5>Réussites : {forma.success}</h5>
                                <h5>Essais : {forma.try}</h5>
                                <h5>Echecs : {forma.try - forma.success}</h5>
                            </div>
                            <div className={'columns actions'}>
                                <div className={'row'}>
                                    <button disabled={!perm.create_forma} onClick={()=>this.props.change(2,forma.id)}><img src={'/assets/images/editer.png'} alt={''}/></button>
                                    <button disabled={!perm.forma_delete} onClick={async () => {
                                        let req = await axios({
                                            url: '/data/formations/admin/' + forma.id + '/delete',
                                            method: 'DELETE',
                                        });
                                        if(req.status === 200){this.componentDidMount()}
                                    }}><img src={'/assets/images/cancel.png'} alt={''}/></button>
                                </div>
                                <h5>public</h5>
                                <div className={'switch-container'}>
                                    <input id={"switch"} checked={forma.public} disabled={!perm.forma_publi} className="payed_switch" type="checkbox" onChange={async () => {
                                        let req = await axios({
                                            url: '/data/formations/admin/' + forma.id + '/visibylity',
                                            method: 'PUT',
                                        });
                                        if(req.status === 201){this.componentDidMount()}
                                    }}/>
                                    <label htmlFor={"switch"} className={"payed_switchLabel"}/>
                                </div>
                            </div>
                        </div>
                    )}
                </section>
            </div>
        );
    }
}
FormaList.contextType = PermsContext;

class CreatorItem extends React.Component {

    constructor(props) {
        super(props);
        this.state= {
            img: null,
            responses: [],
            lasresponseid: 0,
            image: '',
            questionid: null,
            updated: false,
            text: "",
            desc: '',
            correction: '',
            needcorect: false,
        }
        this.save = this.save.bind(this)
        this.addResponse = this.addResponse.bind(this)
        this.deleteResponse = this.deleteResponse.bind(this)
        this.changeBtnResponseState = this.changeBtnResponseState.bind(this)
        this.changeContentResponseState = this.changeContentResponseState.bind(this)
    }

    addResponse(){
        let resp = this.state.responses;
        let id = this.state.lasresponseid +1;
        var b = {
            id:id,
            content: '',
            active:false
        }
        resp.push(b)
        this.setState({responses: resp, lasresponseid: id})

    }

    deleteResponse(id){
        let array = this.state.responses;
        let lenght = array.length;
        let a = 0;
        let obj = 0;
        while(a < lenght){
            if(array[a].id === id){
                obj = a;
            }
            a++;
        }
        array.splice(obj,1);
        this.setState({responses:array, updated:true})
    }

    changeBtnResponseState(id){
        let array = this.state.responses;
        let lenght = array.length;
        let a = 0;
        while(a < lenght){
            if(array[a].id === id){
                array[a].active = !array[a].active;
            }
            a++;
        }
        this.setState({responses:array, updated:true})
    }

    changeContentResponseState(id, content){
        let array = this.state.responses;
        let lenght = array.length;
        let a = 0;
        while(a < lenght){
            if(array[a].id === id){
                array[a].content = content;
            }
            a++;
        }
        this.setState({responses:array, updated:true})
    }

    async componentDidMount() {
        if (this.props.questionid) {
            var req = await axios({
                method: 'GET',
                url: '/data/formations/question/'+this.props.questionid,
            })
            if(req.status === 200){
             this.setState({
                 questionid: this.props.questionid,
                 image: '/storage/formations/question_img/'+this.props.formationid+'/'+req.data.question.img,
                 correction:req.data.correction,
                 desc: req.data.question.desc,
                 text:req.data.question.name,
                 responses: req.data.question.responses,
             })
            }
        }
        window.addEventListener("saveAll", this.save);
    }

    async save() {
        if(this.state.questionid){
            if(this.state.updated){
                var req = await axios({
                    method: 'PUT',
                    url: '/data/formations/admin/question/'+ this.state.questionid + '/update',
                    data: {
                        img: this.state.img,
                        correction: this.state.correction,
                        description: this.state.desc,
                        name: this.state.text,
                        responses: this.state.responses
                    }
                })
                if(req.status ===201){
                    this.setState({updated:false});
                }
            }
        }else{
            var req = await axios({
                method: 'POST',
                url : '/data/formations/' + this.props.formationid + '/admin/question/post',
                data: {
                    img: this.state.img,
                    correction: this.state.correction,
                    description: this.state.desc,
                    name: this.state.text,
                    responses: this.state.responses
                }
            })
            if(req.status === 201){
                this.setState({questionid:req.data.questionid, updated:false})
            }
        }
    }

    createImage(file) {
        let reader = new FileReader();
        reader.onload = (e) => {
            this.setState({
                img: e.target.result
            })
        };
        reader.readAsDataURL(file);
    }

    render() {
        return (
            <section id={'page_'+this.props.id} className={'creator-item ' + (this.props.current ? 'current' : 'hidden')}>
                <form className={'questionadder'}>
                    <div className="question-title">
                        <h1>Question n°{this.props.id}</h1>
                    </div>
                    <div className={'question-main'}>
                        <label>Question</label>
                        <input type={'text'} maxLength={255} value={this.state.text} onChange={(e)=>{this.setState({text: e.target.value, updated:true})}}/>
                    </div>
                    <div className={'add-image'}>
                        <div className={'image'}>
                            {this.state.image &&
                            <img alt={""} src={this.state.image}/>
                            }
                            {!this.state.image &&
                            <h3>ajouter une image 960x540</h3>
                            }
                            <input accept={["image/jpeg", "image/png"]} type={"file"} onChange={(e)=>{
                                let file = e.target.files[0];
                                this.createImage(file)
                                let src = URL.createObjectURL(file)
                                this.setState({image:src, updated:true});
                            }}/>
                        </div>
                    </div>
                    <div className={'response-info'}>
                        <label className={'label-titel'}>Réponses</label>
                        <button className={'btn'} onClick={(e)=>{this.addResponse(); e.preventDefault()}}>ajouter</button>
                    </div>
                    <div className={'responses-list'}>
                        {this.state.responses && this.state.responses.map((resp)=>
                            <div key={resp.ip} className={'response'}>
                                <button id={'btn_'+resp.id} onClick={(e)=>{this.deleteResponse(resp.id); e.preventDefault()}}><img src={'/assets/images/cancel.png'} alt={''}/></button>
                                <input type={'text'} value={resp.content} maxLength={255} onChange={(e)=>{this.changeContentResponseState(resp.id, e.target.value)}}/>
                                <input type="checkbox" checked={resp.active} className={'user'} onClick={(e)=>{this.changeBtnResponseState(resp.id)}}/>
                            </div>
                        )}
                    </div>
                    <div className="description">
                        <label>Description</label>
                        <textarea value={this.state.desc} onChange={(e)=> {this.setState({desc:e.target.value, updated:true})}}/>
                    </div>
                    {this.props.correct &&
                        <div className="correction">
                            <label>Phrase de correction</label>
                            <input type={'text'} maxLength={255} value={this.state.correction} onChange={(e)=> {this.setState({correction:e.target.value, updated:true})}}/>
                        </div>
                    }
                </form>
            </section>
        );
    }
}

class FormaCreate extends React.Component {

    constructor(props) {
        super(props);
        this.state = {
            updated: true,
            item: [{id:0}],
            formationid: null,
            itemid: 0,
            data: true,
            time: false,
            unic_try: false,
            retry_soon: true,
            total: true,
            question :false,
            img:null,
            correction:true,
            getcertif: true,
            saveondeco: true,
            getfinalnote: false,
            name: '',
            time_str: '',
            desc:'',
            max_try: '',
            time_btw: '',
        }
        this.nextSlide = this.nextSlide.bind(this);
        this.prevSlide = this.prevSlide.bind(this);
        this.addSlide = this.addSlide.bind(this);
        this.save = this.save.bind(this);
    }

    nextSlide() {
        const lastIndex = this.state.item.length - 1;
        const resetIndex = this.state.itemid === lastIndex;
        const index = resetIndex ? 0 : this.state.itemid + 1;
        this.setState({
            itemid: index,
        });
    }

    prevSlide(){
        const lastIndex = this.state.item.length -1;
        const resetIndex = this.state.itemid === 0;
        const index = resetIndex ? lastIndex : this.state.itemid - 1;
        this.setState({
            itemid: index,
        });
    }

    addSlide(){
        var list = this.state.item;
        if(this.state.formationid){
            list.push({
                id:list.length
            })
            this.setState({item:list})
        }else{
            this.save(true)
        }
    }

    async componentDidMount() {
        if (this.props.id === null) {
            this.setState({formationid: null});
        } else {
            var req = await axios({
                method: 'GET',
                url: '/data/formations/admin/' + this.props.id + '/get',
            })
            if(req.status ===  200){
                let a = 0;
                let item = this.state.item;
                while (a < req.data.responses.length){
                    item.push({id: item.length, itemid: req.data.responses[a].id});
                    a++;
                }
                let timer = Math.trunc(req.data.formation.timer / 3600);
                let reste = req.data.formation.timer % 3600 / 60
                const final_timer = (timer < 10 ? '0' : '') + timer + ':' + (reste < 10 ? '0' : '') + reste;

                let time = req.data.formation.time_btw_try;
                let time_btw = Math.trunc(time / 86400);
                let rest = time % 86400/ 3600;
                time_btw = (time_btw < 10 ? '0' : '') + time_btw + ' ' + (rest < 10 ? '0' : '') + rest;

                this.setState({
                    formationid: this.props.id,
                    updated:false,
                    img: undefined,
                    item: item,

                    correction: req.data.formation.correction,
                    desc: req.data.formation.desc,
                    name: req.data.formation.name,
                    getcertif: req.data.formation.certify,
                    getfinalnote: req.data.formation.displaynote,
                    max_try: req.data.formation.max_try,
                    time: (req.data.formation.timer !== null),
                    time_str: final_timer,
                    total: req.data.formation.timed,
                    question: req.data.formation.question_timed,
                    retry_soon: req.data.formation.can_retry_later,
                    time_btw: time_btw,
                    unic_try: req.data.formation.unic_try,
                    save: req.data.formation.save_on_deco,
                    image: '/storage/formations/front_img/'+this.props.id+'/'+req.data.formation.image,
                })
            }
        }

        window.addEventListener("saveAll", this.save);
    }

    async save(add = null){
        if(this.state.formationid === null ){
            var req = await axios({
                url: '/data/formations/admin/post',
                method: 'post',
                data: {
                    correction: this.state.correction,
                    desc: this.state.desc,
                    name: this.state.name,
                    certif: this.state.getcertif,
                    finalnote: this.state.getfinalnote,
                    img: this.state.img,
                    max_try: this.state.max_try,
                    time: this.state.time,
                    total: this.state.total,
                    question: this.state.question,
                    time_str: this.state.time_str,
                    time_btw: this.state.retry_soon,
                    time_btw_str: this.state.time_btw,
                    unic_try: this.state.unic_try,
                    save: this.state.saveondeco,
                }
            })
            if(req.status === 201){
                this.setState({updated:false, formationid: req.data.formation.id})
                if(add){
                    var list = this.state.item;
                    list.push({
                        id:list.length
                    })
                    this.setState({item:list, updated:false})
                }
            }

        }else if(this.state.updated){
            var req = await axios({
                method: 'PUT',
                url: '/data/formations/admin/'+ this.state.formationid+'/update',
                data: {
                    correction: this.state.correction,
                    desc: this.state.desc,
                    name: this.state.name,
                    certif: this.state.getcertif,
                    finalnote: this.state.getfinalnote,
                    img: this.state.img,
                    max_try: this.state.max_try,
                    time: this.state.time,
                    total: this.state.total,
                    question: this.state.question,
                    time_str: this.state.time_str,
                    time_btw: this.state.retry_soon,
                    time_btw_str: this.state.time_btw,
                    unic_try: this.state.unic_try,
                    save: this.state.saveondeco,
                }
            })
            if(req.status === 201){
                this.setState({updated: false});
            }
        }
    }

    createImage(file) {
        let reader = new FileReader();
        reader.onload = (e) => {
            this.setState({
                img: e.target.result
            })
        };
        reader.readAsDataURL(file);
    }

    render() {
        return (
            <div className="formationCretor">
                <section className={'header'}>
                    <button className={'btn'} onClick={()=>{this.props.change(1)}}>Quitter</button>
                    <PagesTitle  title={'creer une formation'}/>
                    <button className={'btn'} disabled={this.state.formationid ? false : true} onClick={()=>{window.dispatchEvent(new CustomEvent("saveAll", {}))}}>Enregistrer</button>
                </section>
                <section className={'creator'}>
                    <section className={'creator-items'}>
                        {!this.state.data &&
                            <section id={'loader'}>
                                <div className={'load'}>
                                    <img src={'/assets/images/loading.svg'} alt={''}/>
                                </div>
                            </section>
                        }
                        {this.state.data&&
                            <section id={'page_0'} className={'creator-item ' + (this.state.itemid ===0 ? 'current' : 'hidden')}>
                                <form className={'infos'} onSubmit={(e)=>{
                                e.preventDefault()
                                this.save();
                                }
                                }>
                                    <div className={'name'}>
                                        <label>nom de la formation</label>
                                        <input required type={'text'} value={this.state.name} onChange={(e)=>this.setState({name:e.target.value, updated:true})}/>
                                    </div>
                                    <div className="time">
                                        <div className={'rowed'}>
                                            <label>Temps </label>
                                            <div className={'pilote-btn'}>
                                                <input type="checkbox" checked={this.state.time} id={"time_switch"} onChange={()=>{
                                                    this.setState({time: !this.state.time, updated:true});
                                                }}/>
                                                <div>
                                                    <label htmlFor={"time_switch"}/>
                                                </div>
                                            </div>
                                        </div>
                                        <div className={'time-data-container'}>
                                            <div className={'row time-data ' + (this.state.time ? 'item-current' : 'item-hidden')}>
                                                <div className={'t-q-t-switch'}>
                                                    <label className={'item ' + (this.state.total ? '' :'disabled')} onClick={()=>{
                                                        if(!this.state.total){
                                                            this.setState({total:true, question:false, updated:true})
                                                        }
                                                    }}>total</label>
                                                    <label className={'item ' + (this.state.question ? '' :'disabled')} onClick={()=>{
                                                        if(!this.state.question){
                                                            this.setState({question:true, total:false, updated:true})
                                                        }
                                                    }}>question</label>
                                                </div>
                                                <input type={'time'} value={this.state.time_str} onChange={(e)=>this.setState({time_str:e.target.value, updated:true})}/>
                                            </div>
                                        </div>

                                    </div>
                                    <div className="image">
                                        <div className={'add-image'}>

                                        </div>
                                    </div>
                                    <div className="desc">
                                        <label>Description :</label>
                                        <textarea required value={this.state.desc} onChange={(e)=>this.setState({desc:e.target.value, updated:true})}/>
                                    </div>
                                    <div className="correction rowed">
                                        <label>correction</label>
                                        <div className={'pilote-btn'}>
                                            <input type="checkbox" checked={this.state.correction} id={"correct_switch"} onChange={()=>{
                                                this.setState({correction: !this.state.correction, updated:true});
                                            }}/>
                                            <div>
                                                <label htmlFor={"correct_switch"}/>
                                            </div>
                                        </div>
                                    </div>
                                    <div className="try">
                                        <div className={'rowed'}>
                                            <label>Essai unique</label>
                                            <div className={'pilote-btn'}>
                                                <input type="checkbox" checked={this.state.unic_try} id={"unic_switch"} onChange={()=>{
                                                    this.setState({unic_try: !this.state.unic_try, updated:true});
                                                }}/>
                                                <div>
                                                    <label htmlFor={"unic_switch"}/>
                                                </div>
                                            </div>
                                        </div>
                                        <div className={'try-data-container'}>
                                            <div className={"try-data " + (!this.state.unic_try ? 'item-current' : 'item-hidden')}>
                                                <div className="max-try">
                                                    <label>Nombre d'essai max</label>
                                                    <input type={'number'} placeholder={'0 pour infini'} value={this.state.max_try} onChange={(e)=>this.setState({max_try:e.target.value, updated:true})}/>
                                                </div>
                                                <div className="btwtry">
                                                    <label>Temps entre chaque essai</label>
                                                    <div className={'row'}>
                                                        <div className={'pilote-btn'}>
                                                            <input type="checkbox" checked={this.state.retry_soon} id={"time_btw_try_switch"} onChange={()=>{
                                                                this.setState({retry_soon: !this.state.retry_soon, updated:true});
                                                            }}/>
                                                            <div>
                                                                <label htmlFor={"time_btw_try_switch"}/>
                                                            </div>
                                                        </div>
                                                        {this.state.retry_soon&&
                                                        <div className={'time-btw-try'}>
                                                            <input type={'text'} placeholder={'jj hh'} value={this.state.time_btw} onChange={(e)=>this.setState({time_btw:e.target.value, updated:true})}/>
                                                        </div>
                                                        }
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div className="infos">
                                        <div className="rowed">
                                            <label>Donner la certification</label>
                                            <div className={'pilote-btn'}>
                                                <input type="checkbox" checked={this.state.getcertif} id={"certif_switch"} onChange={()=>{
                                                    this.setState({getcertif: !this.state.getcertif, updated:true});
                                                }}/>
                                                <div>
                                                    <label htmlFor={"certif_switch"}/>
                                                </div>
                                            </div>
                                        </div>
                                        <div className="rowed">
                                            <label>Enregistrer à la déconnexion</label>
                                            <div className={'pilote-btn'}>
                                                <input type="checkbox" checked={this.state.saveondeco} id={"deco_switch"} onChange={()=>{
                                                    this.setState({saveondeco: !this.state.saveondeco, updated:true});
                                                }}/>
                                                <div>
                                                    <label htmlFor={"deco_switch"}/>
                                                </div>
                                            </div>
                                        </div>
                                        <div className="rowed">
                                            <label>Afficher le score à la fin</label>
                                            <div className={'pilote-btn'}>
                                                <input type="checkbox" checked={this.state.getfinalnote} id={"final_switch"} onChange={()=>{
                                                    this.setState({getfinalnote: !this.state.getfinalnote, updated:true});
                                                }}/>
                                                <div>
                                                    <label htmlFor={"final_switch"}/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </section>
                        }
                        {this.state.data && this.state.formationid && this.state.item.map((it)=>
                           it.id !== 0 &&
                              <CreatorItem key={it.id} id={it.id} current={it.id === this.state.itemid} correct={this.state.correction} formationid={this.state.formationid} questionid={it.itemid}/>
                        ) }
                    </section>
                    <section className={'creator-bottom'}>
                        <div className={'items-list'}>
                            {this.state.item.map((it)=>
                                <div key={it.id} id={'page_'+it.id} className={'bottom-item' + (it.id === this.state.itemid ? ' active' : '')}/>
                            )}
                        </div>
                        <div className={'btn-contain'}>
                            <button className={'btn'} disabled={this.state.formationid ? false : true} onClick={this.prevSlide}>&lt;</button>
                            <button className={'btn'}  onClick={this.addSlide}>Ajouter une question</button>
                            <button className={'btn'} disabled={this.state.formationid ? false : true} onClick={this.nextSlide}>&gt;</button>
                        </div>
                    </section>
                </section>
            </div>
        );
    }
}

class AFormaController extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            status: 0,
            formationid: null,
        }
        this.change =this.change.bind(this);
    }

    change(page, formationid =null){
        this.setState({status: page, formationid: formationid})
    }

    render() {
        switch (this.state.status){
            case 0:
                return (<FormaUserList change={this.change}/>)
            case 1:
                return (<FormaList change={this.change}/>)
            case 2:
                return (<FormaCreate change={this.change} id={this.state.formationid} />)
        }
    }
}

export default AFormaController;
