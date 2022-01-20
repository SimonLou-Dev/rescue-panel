import React from 'react';
import PageNavigator from "../../props/PageNavigator";
import Searcher from "../../props/Searcher";

function RapportHoraire(props) {

    const Redirection = (url) => {
        props.history.push(url)
    }

    return (<div className={'TablePage'}>
        <div className={'PageCenter'}>
            <div className={'table-header'}>
                <PageNavigator/>
                <Searcher/>
                <a href={''} target={'_blank'} className={'btn exporter'}><img alt={''} src={'/assets/images/xls.png'}/></a>
                <div className={'selector'}>
                    <input type={'number'} placeholder={'semaine n°'}/>
                    <button><img alt={''} src={'/assets/images/search.png'}/></button>
                </div>
            </div>
            <div className={'table-container'}>
                <table>
                    <thead>
                    <tr>
                        <th>personnel</th>
                        <th>remboursement</th>
                        <th>primes</th>
                        <th>ajoutement horaire</th>
                        <th>total horaire</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td onClick={()=>{Redirection('/personnel/fiche/2')}} className={'link'}>Jean Claude</td>
                        <td>$124</td>
                        <td>$0</td>
                        <td>-15h20</td>
                        <td>187h27</td>
                    </tr>
                    <tr>
                        <td onClick={()=>{Redirection('/personnel/fiche/3')}} className={'link'}>Jean Claude</td>
                        <td>$17.846</td>
                        <td>$784</td>
                        <td>+05h20</td>
                        <td>06h27</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>  )
}

export default RapportHoraire;
