import React from 'react';
import axios from "axios";
import PagesTitle from "../props/utils/PagesTitle";
import TableBottom from "../props/utils/TableBottom";
import dateFormat from "dateformat";


class CarnetVol extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            popup: false,
            user: null,
            page: 1,
            pages: 0,
            datas: null,
            lieux: null,
            place: 0,
            raison:null,
            name:null,
            list: null,
        }
        this.postdata = this.postdata.bind(this)
        this.update = this.update.bind(this)
        this.typing = this.typing.bind(this)

    }

    componentDidMount() {
        this.update()
    }

    async update(){
        let req = await axios({
            method: 'GET',
            url: '/data/vol/get/' + this.state.page + '/' + this.state.name
        });
        if(req.data.status === 'OK'){
            this.setState({
               datas: req.data.datas.vols,
               pages: req.data.datas.pages,
               page: req.data.datas.page,
               lieux: req.data.datas.lieux
            });
        }
    }

    async postdata(e) {
        e.preventDefault();
        let req = await axios({
            method: 'POST',
            url: '/data/vol/add',
            data: {
                lieux: this.state.place,
                raison: this.state.raison
            }
        })
        if (req.status === 201) {
            console.log('into')
            this.setState({data: null, raison: '', lieux: '', popup: false});
            this.update();
        }
    }

    next(){
        if(this.state.page < this.state.pages){
            this.setState({page:this.state.page++})
            this.setState({data: null});
            this.update();
        }
    }

    prev(){
        if(this.state.page > 1){
            this.setState({page:this.state.page--})
            this.setState({data: null});
            this.update();
        }
    }

    async typing(e) {
        let req = await axios({
            url: '/data/vol/searsh/' + e.target.value,
            method: 'GET'
        })
        if(!req.data.datas){
            this.setState({name: '', list: []});
            this.update();
        }else{
            this.setState({list: req.data.datas.users})
            if(req.data.datas.users.length === 1){
                this.setState({name: req.data.datas.users[0].name})
            }

        }
        this.update();
    }
    render() {
        return (
            <div className={'carnetvol'} >
                <section className="head" style={{filter: this.state.popup ? 'blur(5px)' : 'none'}}>
                    <PagesTitle title={'carnet de vol'}/>
                    <button onClick={()=>this.setState({popup: true})} className={'btn'}>ajouter</button>
                </section>
                {this.state.datas &&
                <section className="table-container" style={{filter: this.state.popup ? 'blur(5px)' : 'none'}} >
                    <table>
                        <thead>
                        <tr>
                            <th>n°</th>
                            <th>décollage</th>
                            <th>raison</th>
                            <th>pilote</th>
                            <th>lieux</th>
                        </tr>
                        </thead>
                        <tbody>
                        {this.state.datas.map((vol)=>
                            <tr>
                                <td>{vol.id}</td>
                                <td>{dateFormat(vol.decollage, 'yyyy/mm/dd H:M')} [FR]</td>
                                <td>{vol.raison}</td>
                                <td>{vol.get_user.name}</td>
                                <td>{vol.get_lieux.name}</td>
                            </tr>
                        )}
                        </tbody>
                    </table>
                    <TableBottom placeholder={'rechercher par pilote'} page={this.state.page} pages={this.state.pages} next={this.next} prev={this.prev} typing={this.typing} list={this.state.list}/>
                </section>
                }
                {!this.state.datas &&
                <section className="table-container" style={{filter: this.state.popup ? 'blur(5px)' : 'none'}} >
                    <div className={'load'}>
                        <img src={'/assets/images/loading.svg'} alt={''}/>
                    </div>
                </section>
                }

                {this.state.popup &&
                    <section className="popup">
                        <div className={'center'}>
                            <form onSubmit={this.postdata}>
                                <h2>ajouter un vol</h2>
                                <div className="rowed">
                                    <label>raison du vol</label>
                                    <input type={'text'} max={100} value={this.state.raison} onChange={(e)=>{this.setState({raison: e.target.value})}}/>
                                </div>
                                <div className="rowed">
                                    <label>lieux</label>
                                    <select defaultValue={0} onChange={(e)=>{this.setState({place: e.target.value})}}>
                                        <option value={this.state.place} disabled>choisir</option>
                                        {this.state.lieux &&
                                        this.state.lieux.map((place)=>
                                            <option key={place.id} value={place.id}>{place.name}</option>
                                        )
                                        }
                                    </select>
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
    }
}

export default CarnetVol;
