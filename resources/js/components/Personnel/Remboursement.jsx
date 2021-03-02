import React from 'react';
import axios from "axios";
import TableBottom from "../props/utils/TableBottom";

var admin = false;

class Myview extends React.Component {
    render() {
        return (<div className={'myview'}>
            <section className={'add'}>
                <form>
                    <h2>Ajouter</h2>
                    <select>
                        <option>test</option>
                    </select>
                    <button className={'btn'} type={'submit'}>valider</button>
                </form>
            </section>
            <section className={'list-content'}>
                <div className={'list'}>
                    <div className={'row'}>
                        <h5>00/00/0000 | Chaise de bureau $500</h5>
                        <button><img src={'/assets/images/cancel.png'} alt={''}/></button>
                    </div>
                    <div className={'row'}>
                        <h5>00/00/0000 | Chaise de bureau $500</h5>
                        <button><img src={'/assets/images/cancel.png'} alt={''}/></button>
                    </div>
                    <div className={'row'}>
                        <h5>00/00/0000 | Chaise de bureau $500</h5>
                        <button><img src={'/assets/images/cancel.png'} alt={''}/></button>
                    </div>
                    <div className={'row'}>
                        <h5>00/00/0000 | Chaise de bureau $500</h5>
                        <button><img src={'/assets/images/cancel.png'} alt={''}/></button>
                    </div>
                    <div className={'row'}>
                        <h5>00/00/0000 | Chaise de bureau $500</h5>
                        <button><img src={'/assets/images/cancel.png'} alt={''}/></button>
                    </div>
                    <div className={'row'}>
                        <h5>00/00/0000 | Chaise de bureau $500</h5>
                        <button><img src={'/assets/images/cancel.png'} alt={''}/></button>
                    </div>
                    <div className={'row'}>
                        <h5>00/00/0000 | Chaise de bureau $500</h5>
                        <button><img src={'/assets/images/cancel.png'} alt={''}/></button>
                    </div>
                    <div className={'row'}>
                        <h5>00/00/0000 | Chaise de bureau $500</h5>
                        <button><img src={'/assets/images/cancel.png'} alt={''}/></button>
                    </div>
                    <div className={'row'}>
                        <h5>00/00/0000 | Chaise de bureau $500</h5>
                        <button><img src={'/assets/images/cancel.png'} alt={''}/></button>
                    </div>
                    <div className={'row'}>
                        <h5>00/00/0000 | Chaise de bureau $500</h5>
                        <button><img src={'/assets/images/cancel.png'} alt={''}/></button>
                    </div>
                    <div className={'row'}>
                        <h5>00/00/0000 | Chaise de bureau $500</h5>
                        <button><img src={'/assets/images/cancel.png'} alt={''}/></button>
                    </div>
                </div>
            </section>
        </div>);
    }
}

class Adminview extends React.Component {
    render() {
        return (
          <div className={'adminview'}>
              <div className={'table-head'}>
                  <form>
                      <label>Semaine n°</label>
                      <input type={'number'} value={8}/>
                      <button className={'btn'}>Valider</button>
                  </form>
              </div>
              <table>
                  <thead>
                    <tr>
                        <td className={'head id'}>id</td>
                        <td className={'head pseudo'}>pseudo</td>
                        <td className={'head total'}>total en $</td>
                    </tr>
                  </thead>
                  <tbody>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>
                        <tr>
                            <td className={'id'}>1</td>
                            <td className={'pseudo'}>Simon Lou</td>
                            <td className={'total'}>140$</td>
                        </tr>

                  </tbody>
              </table>
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
