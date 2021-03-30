import React from 'react';
import PagesTitle from "../props/utils/PagesTitle";
import axios from "axios";

class FormaUserList extends React.Component {
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
                                <th className={'forma'}>BC fire unit</th>
                                <th className={'forma'}>BC fire unit</th>
                                <th className={'forma'}>BC fire unit</th>
                                <th className={'forma'}>BC fire unit</th>
                                <th className={'forma'}>BC fire unit</th>
                                <th className={'forma'}>BC fire unit</th>
                                <th className={'forma'}>BC fire unit</th>
                                <th className={'forma'}>BC fire unit</th>
                                <th className={'forma'}>BC fire unit</th>
                                <th className={'forma'}>BC fire unit</th>
                                <th className={'forma'}>BC fire unit</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td className={'name'}>Simon Lou</td>
                                <td className={'forma'}><div className={'pilote-btn'}>
                                    <input type="checkbox" id={"toggle_"+this.props.id }/>
                                    <div>
                                        <label htmlFor={"toggle"+this.props.id}/>
                                    </div>
                                </div></td>
                                <td className={'forma'}><div className={'pilote-btn'}>
                                    <input type="checkbox" id={"toggle"}/>
                                    <div>
                                        <label htmlFor={"toggle"+this.props.id}/>
                                    </div>
                                </div></td>
                                <td className={'forma'}><div className={'pilote-btn'}>
                                    <input type="checkbox" id={"toggle"+this.props.id}/>
                                    <div>
                                        <label htmlFor={"toggle"+this.props.id}/>
                                    </div>
                                </div></td>
                                <td className={'forma'}><div className={'pilote-btn'}>
                                    <input type="checkbox" id={"toggle"+this.props.id}/>
                                    <div>
                                        <label htmlFor={"toggle"+this.props.id}/>
                                    </div>
                                </div></td>
                                <td className={'forma'}><div className={'pilote-btn'}>
                                    <input type="checkbox" id={"toggle"+this.props.id}/>
                                    <div>
                                        <label htmlFor={"toggle"+this.props.id}/>
                                    </div>
                                </div></td>
                                <td className={'forma'}><div className={'pilote-btn'}>
                                    <input type="checkbox" id={"toggle"+this.props.id}/>
                                    <div>
                                        <label htmlFor={"toggle"+this.props.id}/>
                                    </div>
                                </div></td>
                                <td className={'forma'}><div className={'pilote-btn'}>
                                    <input type="checkbox" id={"toggle"+this.props.id}/>
                                    <div>
                                        <label htmlFor={"toggle"+this.props.id}/>
                                    </div>
                                </div></td>
                                <td className={'forma'}><div className={'pilote-btn'}>
                                    <input type="checkbox" id={"toggle"+this.props.id}/>
                                    <div>
                                        <label htmlFor={"toggle"+this.props.id}/>
                                    </div>
                                </div></td>
                                <td className={'forma'}><div className={'pilote-btn'}>
                                    <input type="checkbox" id={"toggle"+this.props.id}/>
                                    <div>
                                        <label htmlFor={"toggle"+this.props.id}/>
                                    </div>
                                </div></td>
                                <td className={'forma'}><div className={'pilote-btn'}>
                                    <input type="checkbox" id={"toggle"+this.props.id}/>
                                    <div>
                                        <label htmlFor={"toggle"+this.props.id}/>
                                    </div>
                                </div></td>
                                <td className={'forma'}><div className={'pilote-btn'}>
                                    <input type="checkbox" id={"toggle"+this.props.id}/>
                                    <div>
                                        <label htmlFor={"toggle"+this.props.id}/>
                                    </div>
                                </div></td>
                            </tr>
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
    }

    render() {
        return (
            <div className="f-formalist">
                <section className="header">
                    <PagesTitle title={'Liste des formations'}/>
                    <button onClick={()=>this.props.change(0)} className={'btn'}>Certifications</button>
                </section>
            </div>
        );
    }
}

class CreatorItem extends React.Component {
    constructor(props) {
        super(props);
        this.state= {
            img: null,
            responses: [],
            lasresponseid: 0,
            image: '',
        }
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
        this.setState({responses:array})
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
        this.setState({responses:array})
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
        this.setState({responses:array})
    }

    componentDidMount() {
        window.addEventListener("saveAll", this.save);
    }

    async save() {
        var req = await axios({
            method: 'post',

        })
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
                        <input type={'text'} maxLength={255}/>
                    </div>
                    <div className={'add-image'}>
                        <div className={'image'}>
                            {this.state.img &&
                                <img alt={""} src={this.state.image}/>
                            }
                            {!this.state.img &&
                                <h3>ajouter une image</h3>
                            }
                            <input accept={["image/jpeg", "image/png"]} type={"file"} onChange={(e)=>{
                                const file = e.target.files[0]
                                this.setState({img:file});
                                console.log(file)
                                let src = URL.createObjectURL(file)
                                this.setState({image:src});
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
                        <textarea />
                    </div>
                    <div className="correction">
                        <label>Phrase de correction</label>
                        <input type={'text'} maxLength={255}/>
                    </div>
                    <button type={"submit"} className={'btn saver'}>Enregistrer</button>
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
        console.log(lastIndex, index, resetIndex)
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

    componentDidMount() {
        window.addEventListener("saveAll", this.save);
    }

    /*
     * bool correction
     * string desc
     * bool certif
     * bool finalnote
     * file img
     * int max_try
     * string name
     * bool time
     * bool total (timed)
     * bool question (timed)
     * string time_str
     * bool unic_try
     * bool time_btw
     * string time_btw_str
     * bool unic_try
     */

    async save(add = null){

        if(this.state.updated){
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
            console.log(req)
            if(req.status === 201){
                this.setState({updated:false, formationid: req.data.formation.id})
                if(add){
                    var list = this.state.item;
                    list.push({
                        id:list.length
                    })
                    this.setState({item:list})
                }
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
                    <button className={'btn'}>Quitter</button>
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
                                            {this.state.img &&
                                            <img alt={""} src={this.state.image}/>
                                            }
                                            {!this.state.img &&
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
                              <CreatorItem key={it.id} id={it.id} current={it.id === this.state.itemid}/>
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
            status: 2,
        }
    }


    render() {
        switch (this.state.status){
            case 0:
                return (<FormaUserList change={(page)=>this.setState({status: page})}/>)
            case 1:
                return (<FormaList change={(page)=>this.setState({status: page})}/>)
            case 2:
                return (<FormaCreate change={(page)=>this.setState({status: page})}/>)
        }
    }
}

export default AFormaController;
