import React from 'react';
import axios from "axios";
import TableBottom from "../props/utils/TableBottom";
import dateFormat from "dateformat";


class Myview extends React.Component {

    constructor(props) {
        super(props);
        this.state = {
            obj: [],
            list: [],
            itemid: 0,
            data: false,
        }
        this.getdata = this.getdata.bind(this);
        this.addItem = this.addItem.bind(this);
        this.delete = this.delete.bind(this);
    }

    componentDidMount() {
        this.getdata();
    }

    async getdata() {
        var req = await axios({
            url: '/data/remboursements/get',
            method: 'GET'
        })
        if (req.status === 200) {
            this.setState({
                list: req.data.remboursements,
                obj: req.data.obj,
                data: true,
            })
        }
    }

    async addItem(e) {
        e.preventDefault();
        var req = await axios({
            url: '/data/remboursements/post',
            method: 'post',
            data: {
                item: this.state.itemid,
            }
        })
        if (req.status === 201) {
            this.setState({itemod: 0})
            this.getdata();
        }
    }

    async delete(id) {
        var req = await axios({
            url: '/data/remboursements/delete/' + id,
            method: 'delete'
        })
        if (req.status === 200) {
            this.getdata();
        }
    }

    render() {
        return (<div className={'myview'}>
            <section className={'add'}>
                {!this.state.data &&
                    <div className={'load'}>
                        <img src={'/assets/images/loading.svg'} alt={''}/>
                    </div>
                }
                {this.state.data &&
                    <form onSubmit={this.addItem}>
                        <h2>Ajouter</h2>
                        <select value={this.state.itemid} onChange={(e)=>{this.setState({itemid: e.target.value})}}>
                            <option value={0}>choisir</option>
                            {this.state.obj && this.state.obj.map((ob)=>
                                <option key={ob.id} value={ob.id}>{ob.name} (${ob.price})</option>
                            )}
                        </select>
                        <button className={'btn'} type={'submit'}>valider</button>
                    </form>
                }
            </section>
            <section className={'list-content'}>
                <div className={'list'}>
                    {!this.state.data &&
                    <div className={'load'}>
                        <img src={'/assets/images/loading.svg'} alt={''}/>
                    </div>
                    }
                    {this.state.data && this.state.list && this.state.list.map((item)=>
                        <div className={'row'}>
                            <h5>{dateFormat(item.created_at, 'dd/mm/yyyy')} | {item.get_item.name} ${item.price}</h5>
                            <button onClick={()=>{this.delete(item.id)}}><img src={'/assets/images/cancel.png'} alt={''}/></button>
                        </div>
                    )}
                </div>
            </section>
        </div>);
    }
}

class Adminview extends React.Component {

    constructor(props) {
        super(props);
        this.state = {
            list:[],
            weeknumber: null,
            maxweek: 1,
            data: false,
        }

        this.getdata = this.getdata.bind(this);
        this.changeweek = this.changeweek.bind(this);
    }

    async getdata(first=false) {
        var req = await axios({
            url: '/data/remboursements/get/admin/' + (this.state.weeknumber === null ? '': this.state.weeknumber),
            method: 'get'
        })
        this.setState({
            maxweek: req.data.maxweek,
            list: req.data.list,
            data: true,
        })
        if(first){
            this.setState({weeknumber:req.data.maxweek})
        }
    }

    changeweek(e){
        e.preventDefault();
        this.getdata()
    }

    componentDidMount() {
        this.getdata(true)
    }

    render() {
        return (
          <div className={'adminview'}>
              <div className={'table-head'}>
                  <form onSubmit={this.changeweek}>
                      <label>Semaine n°</label>
                      <input type={'number'} value={this.state.weeknumber} max={this.state.maxweek} min={1} onChange={(e)=>{this.setState({weeknumber:e.target.value})}}/>
                      <button className={'btn'}>Valider</button>
                  </form>
              </div>
              {!this.state.data &&
              <div className={'load'}>
                  <img src={'/assets/images/loading.svg'} alt={''}/>
              </div>
              }
              {this.state.data &&
              <div className={'table-container'}>
                  <table>
                      <thead>
                      <tr>
                          <td className={'head id'}>id</td>
                          <td className={'head pseudo'}>pseudo</td>
                          <td className={'head total'}>total en $</td>
                      </tr>
                      </thead>
                      <tbody>
                          {this.state.list && this.state.list.map((item)=>
                              <tr key={item.id}>
                                  <td className={'id'}>{item.id}</td>
                                  <td className={'pseudo'}>{item.get_user.name}</td>
                                  <td className={'total'}>${item.total}</td>
                              </tr>
                          )}
                      </tbody>
                  </table>
              </div>
              }
          </div>
        );
    }
}

class Remboursement extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            recap: false,
            me: true,
        }
    }

    render() {
        return (
            <div className={"remboursement"}>
                <div className={'title-contain'}>
                    <h1>remboursement</h1>
                </div>
                <div className={'MainContainer'}>
                    <div className={'selector'}>
                        <button onClick={()=> this.setState({recap: false, me: true})} className={this.state.me ? '' : 'unselected'}>mes remboursements</button>
                        <button onClick={()=> this.setState({recap: true, me: false})} className={this.state.recap ? '' : 'unselected'}>récapitulatif</button>
                    </div>
                    {this.state.me && <Myview/>}
                    {this.state.recap && <Adminview/>}
                </div>
            </div>
        )
    }
}

export default Remboursement;
