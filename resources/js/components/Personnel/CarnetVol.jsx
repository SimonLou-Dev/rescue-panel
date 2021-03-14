import React from 'react';
import axios from "axios";
import PagesTitle from "../props/utils/PagesTitle";
import TableBottom from "../props/utils/TableBottom";


class CarnetVol extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            popup: false
        }
    }

    render() {
        return (
            <div className={'carnetvol'} >
                <section className="head">
                    <PagesTitle title={'carnet de vol'}/>
                    <button onClick={()=>this.setState({popup: true})} className={'btn'}>ajouter</button>
                </section>
                <section className="table-container">
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
                            <tr>
                                <td>00001</td>
                                <td>00/00/0000 à 00h00 [FR]</td>
                                <td>je voulais juste tester</td>
                                <td>Simon Lou</td>
                                <td>lieux</td>
                            </tr>
                            <tr>
                                <td>00001</td>
                                <td>00/00/0000 à 00h00 [FR]</td>
                                <td>je voulais juste tester</td>
                                <td>Simon Lou</td>
                                <td>lieux</td>
                            </tr>
                            <tr>
                                <td>00001</td>
                                <td>00/00/0000 à 00h00 [FR]</td>
                                <td>je voulais juste tester</td>
                                <td>Simon Lou</td>
                                <td>lieux</td>
                            </tr>
                            <tr>
                                <td>00001</td>
                                <td>00/00/0000 à 00h00 [FR]</td>
                                <td>je voulais juste tester</td>
                                <td>Simon Lou</td>
                                <td>lieux</td>
                            </tr>
                            <tr>
                                <td>00001</td>
                                <td>00/00/0000 à 00h00 [FR]</td>
                                <td>je voulais juste tester</td>
                                <td>Simon Lou</td>
                                <td>lieux</td>
                            </tr>
                            <tr>
                                <td>00001</td>
                                <td>00/00/0000 à 00h00 [FR]</td>
                                <td>je voulais juste tester</td>
                                <td>Simon Lou</td>
                                <td>lieux</td>
                            </tr>
                            <tr>
                                <td>00001</td>
                                <td>00/00/0000 à 00h00 [FR]</td>
                                <td>je voulais juste tester</td>
                                <td>Simon Lou</td>
                                <td>lieux</td>
                            </tr>
                            <tr>
                                <td>00001</td>
                                <td>00/00/0000 à 00h00 [FR]</td>
                                <td>je voulais juste tester</td>
                                <td>Simon Lou</td>
                                <td>lieux</td>
                            </tr>
                            <tr>
                                <td>00001</td>
                                <td>00/00/0000 à 00h00 [FR]</td>
                                <td>je voulais juste tester</td>
                                <td>Simon Lou</td>
                                <td>LS</td>
                            </tr>
                            <tr>
                                <td>00001</td>
                                <td>00/00/0000 à 00h00 [FR]</td>
                                <td>je voulais juste tester</td>
                                <td>Simon Lou</td>
                                <td>BC</td>
                            </tr>
                            <tr>
                                <td>00001</td>
                                <td>00/00/0000 à 00h00 [FR]</td>
                                <td>je voulais juste tester</td>
                                <td>Simon Lou</td>
                                <td>lieux</td>
                            </tr>
                            <tr>
                                <td>00001</td>
                                <td>00/00/0000 à 00h00 [FR]</td>
                                <td>je voulais juste tester</td>
                                <td>Simon Lou</td>
                                <td>lieux</td>
                            </tr>
                            <tr>
                                <td>00001</td>
                                <td>00/00/0000 à 00h00 [FR]</td>
                                <td>je voulais juste tester</td>
                                <td>Simon Lou</td>
                                <td>lieux</td>
                            </tr>
                            <tr>
                                <td>00001</td>
                                <td>00/00/0000 à 00h00 [FR]</td>
                                <td>je voulais juste tester</td>
                                <td>Simon Lou</td>
                                <td>lieux</td>
                            </tr>
                            <tr>
                                <td>00001</td>
                                <td>00/00/0000 à 00h00 [FR]</td>
                                <td>je voulais juste tester</td>
                                <td>Simon Lou</td>
                                <td>lieux</td>
                            </tr>
                            <tr>
                                <td>00001</td>
                                <td>00/00/0000 à 00h00 [FR]</td>
                                <td>je voulais juste tester</td>
                                <td>Simon Lou</td>
                                <td>lieux</td>
                            </tr>
                            <tr>
                                <td>00001</td>
                                <td>00/00/0000 à 00h00 [FR]</td>
                                <td>je voulais juste tester</td>
                                <td>Simon Lou</td>
                                <td>lieux</td>
                            </tr>
                            <tr>
                                <td>00001</td>
                                <td>00/00/0000 à 00h00 [FR]</td>
                                <td>je voulais juste tester</td>
                                <td>Simon Lou</td>
                                <td>lieux</td>
                            </tr>
                            <tr>
                                <td>00001</td>
                                <td>00/00/0000 à 00h00 [FR]</td>
                                <td>je voulais juste tester</td>
                                <td>Simon Lou</td>
                                <td>lieux</td>
                            </tr>
                        </tbody>
                    </table>
                    <TableBottom placeholder={'rechercher par pilote'} page={'1'} pages={'10'}/>
                </section>
                {this.state.popup &&
                    <section className="popup">
                        <div className={'center'}>
                            <form>
                                <h2>ajouter un vol</h2>
                                <div className="rowed">
                                    <label>raison du vol</label>
                                    <input type={'text'} max={100}/>
                                </div>
                                <div className="rowed">
                                    <label>lieux</label>
                                    <select defaultValue={1}>
                                        <option value={1} disabled>choisir</option>
                                        <option value={2}>LS</option>
                                        <option value={3}>BC</option>
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
