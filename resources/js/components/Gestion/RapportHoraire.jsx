import React from 'react';
import Row from "../props/Gestion/horaire/Row";
import axios from "axios";
import PagesTitle from "../props/utils/PagesTitle";
import TableBottom from "../props/utils/TableBottom";
import PermsContext from "../context/PermsContext";


class RapportHoraire extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            popup:false,
            service: null,
            maxwwek: 0,
            wek: 0,
            data:false,
            name: '',
            namelist:[],
            action: 0,
            time: '',
        }
        this.update = this.update.bind(this);
        this.submit = this.submit.bind(this);
        this.modifyTime= this.modifyTime.bind(this)
    }

    setdata(bool){
        this.setState({data:bool});
    }

    async componentDidMount() {
        this.setdata(false);
        var req = await axios({
            url: '/data/service/alluser',
            method: 'get'
        })
        this.setState({
            service: req.data.service,
            maxweek: req.data.maxweek,
            wek: req.data.maxweek,
        })
        this.setdata(true);
    }
    async update(){
        this.setdata(false);
        var req = await axios({
            url: '/data/service/alluser/'+this.state.wek,
            method: 'get'
        })
        this.setState({
            service: req.data.service,
        })
        this.setdata(true);
    }

    submit(e){
        e.preventDefault();
        this.update()
    }

    async modifyTime(e) {
        e.preventDefault();
        var req = await axios({
            method: 'PUT',
            url: '/data/service/admin/modify',
            data:{
                name: this.state.name,
                action: this.state.action,
                time: this.state.time,
            }
        });
        if(req.status === 201){
            this.update();
            this.setState({
                name: '',
                action: 0,
                time: '',
                popup:false,
            })
        }
    }


    render() {
        let perm = this.context;
        if(this.state.data){
            return (
                <div className={'RapportHorraire'}>
                    <section className={'header'} style={{filter: this.state.popup ? 'blur(5px)' : 'none'}}>
                        <PagesTitle title={'Rapport horaire'}/>
                        <div className={'semaine-select'}>
                            <form onSubmit={this.submit}>
                                <label>Semaine :</label>
                                <input type={"number"} min={"1"} max={this.state.maxweek} step={"1"} value={this.state.wek} onChange={(e)=>{this.setState({wek:e.target.value})}}/>
                                <button type={'submit'} className={'btn'}>Valider</button>
                            </form>
                        </div>
                        <button className={'btn add-perso'}>Exporter en exel</button>
                        {perm.time_modify ===1&&
                            <button className={'btn add-perso'} onClick={()=>this.setState({popup:true})}>Modifier le temps de service</button>
                        }
                    </section>
                    <section className={'rapport-table-container'} style={{filter: this.state.popup ? 'blur(5px)' : 'none'}}>
                        <div className={'rapport-table'}>
                            <div className={'row table-header'}>
                                <div className={'cell head column-1'}>
                                    <p>agent</p>
                                </div>
                                <div className={'cell head column-8'}>
                                    <p>dimanche</p>
                                </div>
                                <div className={'cell head column-2'}>
                                    <p>lundi</p>
                                </div>
                                <div className={'cell head column-3'}>
                                    <p>mardi</p>
                                </div>
                                <div className={'cell head column-4'}>
                                    <p>mercredi</p>
                                </div>
                                <div className={'cell head column-5'}>
                                    <p>jeudi</p>
                                </div>
                                <div className={'cell head column-6'}>
                                    <p>vendredi</p>
                                </div>
                                <div className={'cell head column-7'}>
                                    <p>samedi</p>
                                </div>
                                <div className={'cell head column-9'}>
                                    <p>total</p>
                                </div>
                                <div className={'cell head column-10'}>
                                    <p>En service ?</p>
                                </div>
                            </div>

                            {this.state.service &&
                            this.state.service.map((item)=>
                                    item.get_user.grade_id > 0 &&
                                        <Row key={item.id} inService={item.get_user.service} itemid={item.id} update={this.update} userid={item.get_user.id} name={item.get_user.name} dimanche={item.dimanche} lundi={item.lundi} mardi={item.mardi} mercredi={item.mercredi} jeudi={item.jeudi} vendredi={item.vendredi} samedi={item.samedi} total={item.total}/>
                            )}

                        </div>
                    </section>
                    {this.state.popup &&
                    <section className="popup">
                        <div className={'center'}>
                            <form onSubmit={this.modifyTime}>
                                <h2>Ajouter/enelever du temps</h2>
                                <div className="rowed">
                                    <label>nom</label>
                                    <input type={'text'}  value={this.state.name} max={100} onChange={(e)=>this.setState({name:e.target.value})}/>
                                </div>
                                <div className="rowed">
                                    <label>action</label>
                                    <select defaultValue={this.state.action} onChange={(e)=>this.setState({action:e.target.value})}>
                                        <option value={0} disabled>choisir</option>
                                        <option value={1}>ajouter</option>
                                        <option value={2}>enelever</option>
                                    </select>
                                </div>
                                <div className="rowed">
                                    <label>temps</label>
                                    <input type={'time'} placeholder={'hh:mm'} value={this.state.time} onChange={(e)=>this.setState({time:e.target.value})}/>
                                </div>
                                <div className={'button'}>
                                    <button onClick={()=>this.setState({popup: false})} className={'btn'}>fermer</button>
                                    <button type={'submit'} className={'btn'}>valider</button>
                                </div>
                            </form>
                        </div>
                    </section>
                    }

                </div>
            )
        }else{
            return(
                <div className={'load'}>
                    <img src={'/assets/images/loading.svg'} alt={''}/>
                </div>
            )
        }


    }
}
RapportHoraire.contextType = PermsContext;
export default RapportHoraire;
