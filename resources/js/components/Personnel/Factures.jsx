import React from 'react';
import dateFormat from "dateformat";
import axios from "axios";
import PagesTitle from "../props/utils/PagesTitle";
import PermsContext from "../context/PermsContext";

class Factures extends React.Component {
    constructor(props) {
        super(props);
        this.state=  {
            list : null,
            addfacture: false,
            payed: false,
            name: "",
            prix: null,
            names: null,
            data: false,
            pdfstart: '',
            pdfend: '',
            errors: [],
        }
        this.paye = this.paye.bind(this);
        this.OnPost = this.OnPost.bind(this);
    }

    async componentDidMount() {
        this.setdata(false);
        var req = await axios({
            url: '/data/facture/list',
            method: 'GET'
        })
        this.setState({list: req.data.impaye});
        this.setdata(true);
    }

    async paye(id) {
        await axios({
            url: '/data/facture/'+ id +'/paye',
            method: 'PUT',
        })
        this.componentDidMount();
    }

    async ShearshChange(event) {
        this.setState({name: event.target.value});
        if(event.target.value !== ""){
            var req = await axios({
                url: '/data/patient/search/' + event.target.value,
                method: 'GET',
            });
            this.setState({names: req.data.list})
        }
    }

    onchange(e){
        if(this.state.payed){
            this.setState({payed:false})
        }else{
            this.setState({payed:true})
        }
    }

    async OnPost(e) {
        e.preventDefault();
        await axios({
            url: '/data/facture/add',
            method: 'POST',
            data: {
                payed: this.state.payed,
                name: this.state.name,
                montant: this.state.prix,
            }
        }).then(response => {
            if(response.status === 201){
                this.setState({addfacture:false, name: "", payed:false, prix:null,});
                this.componentDidMount();
            }
        }).catch(error => {
            error = Object.assign({}, error);
            this.setState({error: true});
            if(error.response.status === 422){
                this.setState({errors: error.response.data.errors})
            }
        })

    }
    setdata(bool){
        this.setState({data:bool});
    }

    render() {
        const perm = this.context;
        return (
            <div className={"impayes"}>
                <section className={'header'} style={{filter: this.state.addfacture ? 'blur(5px)' : 'none'}}>
                    <div className={'title-contain'}>
                        <PagesTitle title={'Factures'}/>
                    </div>
                    <div className={'Add-facture'}>
                        <button className={'btn'} disabled={(perm.add_factures !== 1)} onClick={()=>{this.setState({addfacture:true})}}>Ajouter une facture</button>
                    </div>
                    {perm.factures_PDF === 1 &&
                        <div className={'pdf_Generator mobildisabled'} >
                            <form onSubmit={(e)=>{
                                e.preventDefault();
                                window.open('/PDF/facture/'+this.state.pdfstart+'/'+this.state.pdfend)
                            }
                            }>
                                <label>Liste des impayés du</label>
                                <input type={"date"} value={this.state.pdfstart} onChange={(e)=>{this.setState({pdfstart:e.target.value})}}/>
                                <label>au</label>
                                <input type={"date"} value={this.state.pdfend} onChange={(e)=>{this.setState({pdfend:e.target.value})}}/>
                                <button type={"submit"} className={'btn'}>générer</button>
                            </form>
                        </div>
                    }
                </section>
                {this.state.addfacture  &&
                    <div className={'add-facture-form'}>
                        <div className={'card-facture'}>
                            <h1>Ajouter une facture :</h1>
                            <form onSubmit={this.OnPost}>
                                <div className={'content'}>
                                    <input list={'autocomplete'} autoComplete={'off'} className={(this.state.errors.name ? 'form-error': '')} value={this.state.name} type={'text'} placeholder={'Patient'} onChange={event => {this.ShearshChange(event)}}/>
                                    <datalist id="autocomplete">
                                        {this.state.names &&
                                            this.state.names.map((option) =>
                                                <option key={option.id} value={option.vorname + ' ' + option.name} />
                                            )
                                        }
                                    </datalist>
                                    {this.state.errors.name &&
                                    <ul className={'error-list'}>
                                        {this.state.errors.name.map((item)=>
                                            <li>{item}</li>
                                        )}
                                    </ul>
                                    }
                                    <input type={'number'} placeholder={'prix en $'} className={(this.state.errors.montant ? 'form-error': '')} value={this.state.prix} onChange={event => {this.setState({prix: event.target.value})}}/>
                                    {this.state.errors.montant &&
                                    <ul className={'error-list'}>
                                        {this.state.errors.montant.map((item)=>
                                            <li>{item}</li>
                                        )}
                                    </ul>
                                    }
                                    <div className={'switch-container'}>
                                        <input id={"switch1"} checked={this.state.payed} className="payed_switch" type="checkbox" onChange={event => {this.onchange(event)}}/>
                                        <label htmlFor={"switch1"} className={"payed_switchLabel"}/>
                                    </div>
                                </div>
                                <div className={'footer'}>
                                    <button className={'btn'} onClick={()=>this.setState({addfacture:false})}>fermer</button>
                                    <button className={'btn'} type={'submit'}>Ajouter</button>
                                </div>
                            </form>
                        </div>
                    </div>
                }

                <section className={'impayelist'} style={{filter: this.state.addfacture ? 'blur(5px)' : 'none'}}>
                    {!this.state.data &&
                    <div className={'load'}>
                        <img src={'/assets/images/loading.svg'} alt={''}/>
                    </div>
                    }
                    {this.state.data&&<div className={'ImpayeTableContainer'}>

                        <table>
                            <thead>
                                <tr>
                                    <td className={'head'}>Date</td>
                                    <td className={'head'}>Nom prénom</td>
                                    <td className={'head'}>heure</td>
                                    <td className={'head'}>Montant</td>
                                    <td className={'head'}>payé</td>
                                </tr>
                            </thead>


                            <tbody>
                            {this.state.list &&
                            this.state.list.map((item) =>
                                <tr key={item.id}>
                                    <td>{dateFormat(item.created_at, 'dd/mm/yyyy')}</td>
                                    <td>{item.get_patient.vorname + ' ' + item.get_patient.name}</td>
                                    <td>{dateFormat(item.created_at, 'H:MM')}</td>
                                    <td>{item.price}$</td>
                                    <td>
                                        <div className={'switch-container'}>
                                            <input id={"switch"+item.id} className="payed_switch" type="checkbox" onChange={(e) => {this.paye(item.id)}}/>
                                            <label htmlFor={"switch"+item.id} className={"payed_switchLabel"}/>
                                        </div>
                                    </td>
                                </tr>
                            )}
                            </tbody>


                        </table>
                    </div>}
                </section>
            </div>

        )
    }
}
Factures.contextType=PermsContext;

export default Factures;
