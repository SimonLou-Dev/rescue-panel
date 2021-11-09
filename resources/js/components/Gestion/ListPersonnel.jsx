import axios from 'axios';
import React from 'react';
import PersonnelLine from "../props/Gestion/Personnel/PersonnelLine";
import PagesTitle from "../props/utils/PagesTitle";
import TableBottom from "../props/utils/TableBottom";
import {Link} from "react-router-dom";
import PermsContext from "../context/PermsContext";

class ListPersonnel extends React.Component {
    constructor(props){
        super(props);
        this.state = {
            userlist: null,
            data:false,
            select:0,
            orderby:'id',
            oderdir:'asc',
            number: '?'
        }
        this.getdata = this.getdata.bind(this)
        this.only = this.only.bind(this)
        this.order = this.order.bind(this)
    }

    async componentDidMount(){
        this.getdata()
    }

    async getdata(dir, orderby) {
        if(orderby === undefined) {orderby = this.state.orderby}
        if(dir === undefined) {dir = this.state.oderdir}

        var req = await axios({
            url: '/data/users/getall?orderBy='+orderby + '&oderdir='+ dir+ (this.state.select !== 0 ? '&grade='+this.state.select:''),
            method: 'GET'
        })
        this.setState({
            userlist: req.data.users,
            number: req.data.number,
            data: true
        })
    }
    only(e){
        e.preventDefault();
        this.getdata();
    }

    order(item){
        var dir;
        var orderby = item
        if(item ===  this.state.orderby){
            dir = this.state.oderdir === 'asc' ? 'desc':'asc';
            this.setState({oderdir: dir})
        }else{
            dir = 'asc'
            this.setState({orderby:item, oderdir:'asc'})
        }
        this.getdata(dir,orderby);
    }

    render() {
        const perm = this.context;
        if(this.state.data){
            return (
                <div className={"PersonnelList"}>
                    <section className={'header'}>
                        {perm.primesupdate_request === 1 &&
                        <button className={'btn'}><Link to={'/gestion/primes-request'} >Primes</Link></button>
                        }
                        <PagesTitle title={'Liste du personnel'}/>
                        {perm.edit_perm === 1 &&
                            <Link to={'/gestion/perm'} className={'btn'}>gérer les permissions</Link>
                        }
                        {perm.edit_perm === 1 &&
                            <a href={'/data/users/export'} target={'_blank'} className={'btn'}>Exporter</a>
                        }

                    </section>
                    <section className={'list-personnel'}>
                        <div className={'tableHeader'}>
                            <form onSubmit={this.only}>
                                <label>Afficher uniquement : </label>
                                <select value={this.state.select} onChange={(e)=>{this.setState({select: e.target.value})}}>
                                    <option value={0}>Tout le monde</option>
                                    <optgroup label={'pas d\'accès'}>
                                        <option value={1}>user</option>
                                    </optgroup>
                                    <optgroup label={'membre'}>
                                        <option value={2}>Probies</option>
                                        <option value={3}>Engineer</option>
                                        <option value={4}>Firefighter</option>
                                        <option value={5}>Senior Firefighter</option>
                                    </optgroup>
                                    <optgroup label={'référents'}>
                                        <option value={6}>Lead Firefighter</option>
                                        <option value={7}>Fire Marshall</option>
                                    </optgroup>
                                    <optgroup label={'direction'}>
                                        <option value={8}>Assistant Chief</option>
                                        <option value={9}>Chief</option>
                                    </optgroup>
                                    <optgroup label={'autre'}>
                                        <option value={10}>Inspecteur</option>
                                        <option value={11}>Développeur</option>
                                    </optgroup>
                                </select>
                                <button Type={'submit'} className={'btn'}>Trier</button>
                            </form>
                            <div className={'secondPart'}>
                                <h4>{this.state.number} item affiché(s)</h4>
                                <button className={'btn'} onClick={() => {this.setState({
                                    select:0,
                                    orderby:'id',
                                    oderdir:'asc',
                                })
                                    this.getdata();
                                }}>reset</button>
                            </div>
                        </div>
                        <table>
                            <thead>
                            <tr>
                                <th className={'id'} onClick={() => this.order('id')}><div className={'orderable'}> id<div className={'order'}><img src={'/assets/images/bottom_arrow.svg'} className={'reverse '+ ((this.state.orderby === 'id' && this.state.oderdir === 'asc')? 'higlights':'')}/><img className={this.state.orderby === 'id' && this.state.oderdir === 'desc'? 'higlights':''} src={'/assets/images/bottom_arrow.svg'}/></div></div></th>
                                <th className={'name'}>nom prénom</th>
                                <th className={'matricule'} onClick={() => this.order('matricule')} ><div className={'orderable'}>matricule <div className={'order'}><img className={'reverse '+ (this.state.orderby === 'matricule' && this.state.oderdir === 'asc'? 'higlights':'')} src={'/assets/images/bottom_arrow.svg'}/><img className={this.state.orderby === 'matricule' && this.state.oderdir === 'desc'? 'higlights':''} src={'/assets/images/bottom_arrow.svg'}/></div></div></th>
                                <th className={'tel'}>n° de tel</th>
                                <th className={'compte'}>n° de compte</th>
                                <th className={'discordId'}>discord ID</th>
                                <th className={'grade'} onClick={() => this.order('grade_id')} ><div className={'orderable'}>grade<div className={'order'}><img className={'reverse ' + (this.state.orderby === 'grade_id' && this.state.oderdir === 'asc'? 'higlights':'')} src={'/assets/images/bottom_arrow.svg'}/><img className={this.state.orderby === 'grade_id' && this.state.oderdir === 'desc'? 'higlights':''} src={'/assets/images/bottom_arrow.svg'}/></div></div></th>
                                <th className={'pilote'}>pilote</th>
                            </tr>
                            </thead>
                            <tbody>
                            {this.state.userlist && this.state.userlist.map((user)=>
                                <PersonnelLine id={user.id} key={user.id} discordid={user.discord_id} pilote={user.pilote} matricule={user.matricule} name={user.name} compte={user.compte} tel={user.tel} grade={user.grade_id} update={this.getdata}/>
                            )}
                            </tbody>
                        </table>
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
ListPersonnel.contextType = PermsContext;
export default ListPersonnel;
