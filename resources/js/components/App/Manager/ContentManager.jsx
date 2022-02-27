import React, {useEffect, useState} from 'react';
import CardComponent from "../../props/CardComponent";
import axios from "axios";

function ContentManager(props) {
    const [data, setData] = useState(null);

    const[ interValue, setintervalue] = useState('');
    const[ hospValue, sethospvalue] = useState('');
    const[ typeValue, settypevalue] = useState('');
    const[ blessuresValue, setBlessuresvalue] = useState('');
    const[ colorValue, setcolorvalue] = useState('');
    const[ survolValue, setSurvolvalue] = useState('');
    const[ pathoValue, setpathovalue] = useState('');
    const[ pathoDesc, setpathoDesc] = useState('');

    useEffect(async () => {
        await axios({
            method: 'GET',
            url: '/data/gestion/content'
        }).then(r => {
            setData(r.data)
        })
    }, []);

    const postContent = async (number, name, desc = null) => {
        await axios({
            method: 'POST',
            url: '/data/gestion/content/'+number,
            data:{
                name,
                desc
            }
        }).then(r => {
            setData(r.data)
        })
    }

    const deleteContent = async (number, id) => {
        await axios({
            method: 'DELETE',
            url: '/data/gestion/content/'+number+'/'+id
        }).then(r => {
            setData(r.data)
        })
    }

    return (<div className={'ContentManager'}>
        <CardComponent title={'Types d\'interventions'}>
            <div className={'list'}>
                <table>
                    <tbody>
                    {data && data.interventions.map((i)=>
                        <tr key={'inter'+ i.id}>
                            <td className={'name'}>{i.name}</td>
                            <td><img alt={''} src={'/assets/images/decline.png'} onClick={()=>{deleteContent(1,i.id)}} /></td>
                        </tr>
                    )}
                    </tbody>
                </table>
            </div>
            <div className={'form'}>
                <input type={"text"} placeholder={'nom'} value={interValue} onChange={(e)=>{setintervalue(e.target.value)}}/>
                <button className={'btn'}><img alt={''} src={'/assets/images/add.png'} onClick={()=>{postContent(1, interValue)}} /></button>
            </div>
        </CardComponent>
        <CardComponent title={'Transports'}>
            <div className={'list'}>
                <table>
                    <tbody>
                    {data && data.hospital.map((i)=>
                        <tr key={'hosp'+ i.id}>
                            <td className={'name'}>{i.name}</td>
                            <td><img alt={''} src={'/assets/images/decline.png'} onClick={()=>{deleteContent(2,i.id)}}/></td>
                        </tr>
                    )}
                    </tbody>
                </table>
            </div>
            <div className={'form'}>
                <input type={"text"} placeholder={'nom'} value={hospValue} onChange={(e)=>{sethospvalue(e.target.value)}}/>
                <button className={'btn'}><img alt={''} src={'/assets/images/add.png'} onClick={()=>{postContent(2, hospValue)}}/></button>
            </div>
        </CardComponent>
        <CardComponent title={'Types de BC'}>
            <div className={'list'}>
                <table>
                    <tbody>
                    {data && data.BCTypes.map((i)=>
                        <tr key={'BCTypes'+ i.id}>
                            <td className={'name'}>{i.name}</td>
                            <td><img alt={''} src={'/assets/images/decline.png'} onClick={()=>{deleteContent(3,i.id)}}/></td>
                        </tr>
                    )}
                    </tbody>
                </table>
            </div>
            <div className={'form'}>
                <input type={"text"} placeholder={'nom'} value={typeValue} onChange={(e)=>{settypevalue(e.target.value)}}/>
                <button className={'btn'}><img alt={''} src={'/assets/images/add.png'} onClick={()=>{postContent(3, typeValue)}}/></button>
            </div>
        </CardComponent>
        <CardComponent title={'Blessures'}>
            <div className={'list'}>
                <table>
                    <tbody>
                    {data && data.Blessures.map((i)=>
                        <tr key={'Blessures'+ i.id}>
                            <td className={'name'}>{i.name}</td>
                            <td><img alt={''} src={'/assets/images/decline.png'} onClick={()=>{deleteContent(4,i.id)}}/></td>
                        </tr>
                    )}
                    </tbody>
                </table>
            </div>
            <div className={'form'}>
                <input type={"text"} placeholder={'nom'} value={blessuresValue} onChange={(e)=>{setBlessuresvalue(e.target.value)}}/>
                <button className={'btn'}><img alt={''} src={'/assets/images/add.png'} onClick={()=>{postContent(4, blessuresValue)}}/></button>
            </div>
        </CardComponent>
        <CardComponent title={'Couleur vètements'}>
            <div className={'list'}>
                <table>
                    <tbody>
                    {data && data.Color.map((i)=>
                        <tr key={'Color'+ i.id}>
                            <td className={'name'}>{i.name}</td>
                            <td><img alt={''} src={'/assets/images/decline.png'} onClick={()=>{deleteContent(5,i.id)}}/></td>
                        </tr>
                    )}
                    </tbody>
                </table>
            </div>
            <div className={'form'}>
                <input type={"text"} placeholder={'nom'} value={colorValue} onChange={(e)=>{setcolorvalue(e.target.value)}}/>
                <button className={'btn'}><img alt={''} src={'/assets/images/add.png'} onClick={()=>{postContent(5, colorValue)}}/></button>
            </div>
        </CardComponent>
        <CardComponent title={'Lieux de survol'}>
            <div className={'list'}>
                <table>
                    <tbody>
                    {data && data.LieuxSurvol.map((i)=>
                        <tr key={'LieuxSurvol'+ i.id}>
                            <td className={'name'}>{i.name}</td>
                            <td><img alt={''} src={'/assets/images/decline.png'} onClick={()=>{deleteContent(6,i.id)}}/></td>
                        </tr>
                    )}
                    </tbody>
                </table>
            </div>
            <div className={'form'}>
                <input type={"text"} placeholder={'nom'} value={survolValue} onChange={(e)=>{setSurvolvalue(e.target.value)}}/>
                <button className={'btn'}><img alt={''} src={'/assets/images/add.png'} onClick={()=>{postContent(6, survolValue)}}/></button>
            </div>
        </CardComponent>
        <CardComponent title={'Pathologies'}>
            <div className={'list'}>
                <table>
                    <tbody>
                    {data && data.Pathologies.map((i)=>
                        <tr key={'Pathologies'+ i.id}>
                            <td className={'name'}>{i.name}</td>
                            <td><img alt={''} src={'/assets/images/decline.png'} onClick={()=>{deleteContent(7,i.id)}}/></td>
                        </tr>
                    )}
                    </tbody>
                </table>
            </div>
            <div className={'form-diviser'}>
                <div className={'form-divider'}>
                    <textarea value={pathoDesc} onChange={(e)=>{setpathoDesc(e.target.value)}}/>
                </div>
                <div className={'form-divider'}>
                    <input type={"text"} placeholder={'nom'} value={pathoValue} onChange={(e)=>{setpathovalue(e.target.value)}}/>
                    <button className={'btn'}><img alt={''} src={'/assets/images/add.png'} onClick={()=>{postContent(7, pathoValue, pathoDesc)}}/></button>
                </div>
            </div>
        </CardComponent>

    </div> )
}

export default ContentManager;
