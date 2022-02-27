import React, {useEffect, useState} from 'react';
import axios from "axios";

function DiscordBots(props) {
    const [channel, setChannels] = useState(null);

    useEffect(async () => {
        await axios({
            url: '/data/management/discord',
            method: 'GET'
        }).then(r => {
            setChannels(r.data.channels)
        })
    }, [])

    const changeChannel = (item, value) => {
        setChannels(prevState => ({
            ...prevState,
            [item]: value,
        }))
    }

    return (<div className={'TablePage'}>
        {channel &&
            <div className={'page-center'}>
                <div className={'header'}>
                    <h4>Liste des channels discord (WEBHOOK ou DISCORD CHANNEL ID)</h4>
                    <button className={'btn'} onClick={async () => {
                    await  axios({
                        method: 'PUT',
                        url: '/data/management/discord',
                        data: {
                            channel,
                        }
                    }).then(r => {
                        setChannels(r.data.channels)
                    })
                    }}>
                        <img src={'/assets/images/save.png'} alt={''}/>
                    </button>
                </div>
                <div className={'container'}>
                    <div className={'chann-item'}>
                        <label>Erreurs</label>
                        <input type={'text'} value={channel.errors} onChange={(e)=>{changeChannel(Object.keys(channel)[0],e.target.value)}}/>
                    </div>
                    <div className={'chann-item'}>
                        <label>Bugs</label>
                        <input type={'text'} value={channel.bugs} onChange={(e)=>{changeChannel(Object.keys(channel)[1],e.target.value)}}/>
                    </div>
                    <div className={'chann-item'}>
                        <label>service</label>
                        <input type={'text'} value={channel.service} onChange={(e)=>{changeChannel(Object.keys(channel)[2],e.target.value)}}/>
                    </div>
                    <div className={'chann-item'}>
                        <label>Annonces SAMS</label>
                        <input type={'text'} value={channel.MedicAnnonce} onChange={(e)=>{changeChannel(Object.keys(channel)[3],e.target.value)}}/>
                    </div>
                    <div className={'chann-item'}>
                        <label>FireAnnonce</label>
                        <input type={'text'} value={channel.FireAnnonce} onChange={(e)=>{changeChannel(Object.keys(channel)[4],e.target.value)}}/>
                    </div>
                    <div className={'chann-item'}>
                        <label>BC</label>
                        <input type={'text'} value={channel.BC} onChange={(e)=>{changeChannel(Object.keys(channel)[5],e.target.value)}}/>
                    </div>
                    <div className={'chann-item'}>
                        <label>RI</label>
                        <input type={'text'} value={channel.RI} onChange={(e)=>{changeChannel(Object.keys(channel)[6],e.target.value)}}/>
                    </div>
                    <div className={'chann-item'}>
                        <label>Factures</label>
                        <input type={'text'} value={channel.Facture} onChange={(e)=>{changeChannel(Object.keys(channel)[7],e.target.value)}}/>
                    </div>
                    <div className={'chann-item'}>
                        <label>vols</label>
                        <input type={'text'} value={channel.vols} onChange={(e)=>{changeChannel(Object.keys(channel)[8],e.target.value)}}/>
                    </div>
                    <div className={'chann-item'}>
                        <label>MedicInfos (n°tel, n°compte, ...)</label>
                        <input type={'text'} value={channel.MedicInfos} onChange={(e)=>{changeChannel(Object.keys(channel)[9] ,e.target.value)}}/>
                    </div>
                    <div className={'chann-item'}>
                        <label>FireInfos (n°tel, n°compte, ...)</label>
                        <input type={'text'} value={channel.FireInfos} onChange={(e)=>{changeChannel(Object.keys(channel)[10],e.target.value)}}/>
                    </div>
                    <div className={'chann-item'}>
                        <label>MedicRemboursement</label>
                        <input type={'text'} value={channel.MedicRemboursement} onChange={(e)=>{changeChannel(Object.keys(channel)[11],e.target.value)}}/>
                    </div>
                    <div className={'chann-item'}>
                        <label>FireRemboursement</label>
                        <input type={'text'} value={channel.FireRemboursement} onChange={(e)=>{changeChannel(Object.keys(channel)[12],e.target.value)}}/>
                    </div>
                    <div className={'chann-item'}>
                        <label>staff (non utilisé)</label>
                        <input type={'text'} value={channel.staff} onChange={(e)=>{changeChannel(Object.keys(channel)[13],e.target.value)}}/>
                    </div>
                    <div className={'chann-item'}>
                        <label>MedicLogistique (Gestion stock + attrivbution matériel)</label>
                        <input type={'text'} value={channel.MedicLogistique} onChange={(e)=>{changeChannel(Object.keys(channel)[14],e.target.value)}}/>
                    </div>
                    <div className={'chann-item'}>
                        <label>FireLogistique (Gestion stock + attrivbution matériel)</label>
                        <input type={'text'} value={channel.FireLogistique} onChange={(e)=>{changeChannel(Object.keys(channel)[15],e.target.value)}}/>
                    </div>
                    <div className={'chann-item'}>
                        <label>MedicSanctions</label>
                        <input type={'text'} value={channel.MedicSanctions} onChange={(e)=>{changeChannel(Object.keys(channel)[16],e.target.value)}}/>
                    </div>
                    <div className={'chann-item'}>
                        <label>FireSanctions</label>
                        <input type={'text'} value={channel.FireSanctions} onChange={(e)=>{changeChannel(Object.keys(channel)[17],e.target.value)}}/>
                    </div>
                    <div className={'chann-item'}>
                        <label>Test de poudre</label>
                        <input type={'text'} value={channel.poudre} onChange={(e)=>{changeChannel(Object.keys(channel)[18],e.target.value)}}/>
                    </div>
                    <div className={'chann-item'}>
                        <label>Absences</label>
                        <input type={'text'} value={channel.Absences} onChange={(e)=>{changeChannel(Object.keys(channel)[19],e.target.value)}}/>
                    </div>
                </div>
            </div>
        }
    </div> )
}

export default DiscordBots;
