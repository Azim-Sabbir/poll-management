import React from 'react';
import ReactDOM from 'react-dom/client';
import './bootstrap';
import { BrowserRouter as Router, Routes, Route, Link, BrowserRouter } from 'react-router-dom';

import Poll from "./pages/Poll.jsx";
import PollList from "./pages/PollList.jsx";

function App() {
    return (
        <BrowserRouter basename="/poll">
            <Routes>
                <Route path="/" element={<PollList/>} />
                <Route path="/:slug" element={<Poll />} />
            </Routes>
        </BrowserRouter>
    );
}

const root = ReactDOM.createRoot(document.getElementById('app'));
root.render(<App />);
