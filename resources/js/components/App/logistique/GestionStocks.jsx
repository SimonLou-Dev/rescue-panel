import React from 'react';
import PageNavigator from "../../props/PageNavigator";
import Searcher from "../../props/Searcher";
import {Link} from "react-router-dom";

function GestionStocks(props) {

    const Redirection = (url) => {
        props.history.push(url)
    }

    return (<div className={'StockManager'}>
        <div className={'StockManagerCenter'}>
            <div className={'table-header'}>
                <PageNavigator/>
                <Searcher/>
                <a href={''} target={'_blank'} className={'btn exporter'}><img alt={''} src={'/assets/images/xls.png'}/></a>
                <button onClick={()=>{
                    Redirection('/zz/logistique/stock/settings')
                }} className={'btn exporter'}><img alt={''} src={'/assets/images/settings.png'}/></button>
            </div>
            <div className={'table-container'}>
                <table>
                    <thead>
                    <tr>
                        <th>item</th>
                        <th>Paleto</th>
                        <th>Sandy</th>
                        <th>PillBox</th>
                        <th>total</th>
                        <th/>
                    </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Lance à incendie</td>
                            <td>12</td>
                            <td>14</td>
                            <td>22</td>
                            <td>45</td>
                            <td><button className={'btn'}><img alt={''} src={'/assets/images/edit.png'}/></button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>)
}

export default GestionStocks;
