import React from 'react';
import PageNavigator from "../../props/PageNavigator";
import Searcher from "../../props/Searcher";

function ListPersonnel(props) {

    const Redirection = (url) => {
        props.history.push(url)
    }

    return (<div className={'TablePage'}>
        <div className={'PageCenter'}>
            <div className={'table-header'}>
                <PageNavigator/>
                <Searcher/>
                <a href={''} target={'_blank'} className={'btn exporter'}><img alt={''} src={'/assets/images/xls.png'}/></a>
            </div>
            <div className={'table-container'}>
                <table>
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>nom</th>
                        <th>matricule</th>
                        <th>tel</th>
                        <th>discord id</th>
                        <th>grade</th>
                        <th>spétialité</th>
                        <th>service</th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td onClick={()=>{Redirection('/personnel/fiche/2')}} className={'link'}>Jean Claude</td>
                            <td>12</td>
                            <td>555-7846</td>
                            <td>805512471339204618</td>
                            <td><select>
                                <option>User</option>
                                <option>Test</option>
                            </select></td>
                            <td>805512471339204618</td>
                            <td><button className={'btn'}><img alt={''} src={'/assets/images/decline.png'}/></button></td>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td onClick={()=>{Redirection('/personnel/fiche/2')}}  className={'link'}>Jean Claude</td>
                            <td>12</td>
                            <td>555-7846</td>
                            <td>805512471339204618</td>
                            <td><select>
                                <option>Fire Engininer Chief</option>
                                <option>User</option>
                            </select></td>
                            <td>805512471339204618</td>
                            <td><button className={'btn'}><img alt={''} src={'/assets/images/accept.png'}/></button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div> )
}

export default ListPersonnel;
