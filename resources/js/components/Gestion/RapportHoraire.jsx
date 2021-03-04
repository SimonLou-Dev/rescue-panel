import React from 'react';
import Row from "../props/Gestion/horaire/Row";
import axios from "axios";
import PagesTitle from "../props/utils/PagesTitle";
import TableBottom from "../props/utils/TableBottom";


class RapportHoraire extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            service: null,
            maxwwek: 0,
            wek: 0,
            data:false,
        }
        this.update = this.update.bind(this);
        this.submit = this.submit.bind(this);
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


    render() {
        if(this.state.data){
            return (


                <div className={'RapportHorraire'}>
                    <section className={'header'}>
                        <PagesTitle title={'Rapport horaire'}/>
                        <div className={'semaine-select'}>
                            <form onSubmit={this.submit}>
                                <label>Semaine :</label>
                                <input type={"number"} min={"1"} max={this.state.maxweek} step={"1"} value={this.state.wek} onChange={(e)=>{this.setState({wek:e.target.value})}}/>
                                <button type={'submit'} className={'btn'}>Valider</button>
                            </form>
                        </div>
                        <button className={'btn add-perso'} onClick={async (e)=>{
                            var req = await axios({
                                url: '/data/service/addwors',
                                method: 'GET'
                            })
                            if(req.status === 201){
                                this.update();
                            }
                        }}>Ajouter tout le personnel</button>
                    </section>
                    <section className={'rapport-table-container'}>
                        <div className={'rapport-table'}>
                            <div className={'row table-header'}>
                                <div className={'cell head column-1'}>
                                    <p>agent</p>
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
                                <div className={'cell head column-8'}>
                                    <p>dimanche</p>
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
                                item.user.grade < 10 &&
                                <Row key={item.id} inService={item.user.OnService} itemid={item.id} update={this.update} userid={item.user.id} name={item.user.name} dimanche={item.dimanche} lundi={item.lundi} mardi={item.mardi} mercredi={item.mercredi} jeudi={item.jeudi} vendredi={item.vendredi} samedi={item.samedi} total={item.total}/>
                            )
                            }

                        </div>
                        <TableBottom placeholder={'rechercher un nom'} page={1} pages={5}/>
                    </section>

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

export default RapportHoraire;
