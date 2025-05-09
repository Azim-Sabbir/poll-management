import React, { useEffect, useState } from 'react';
import { Link } from 'react-router-dom'; // Import Link for navigation

const Poll = () => {
    const [selectedOption, setSelectedOption] = useState(null);
    const [totalVotes, setTotalVotes] = useState(0);
    const [poll, setPoll] = useState(null);
    const [options, setOptions] = useState([]);
    const [loading, setLoading] = useState(true);

    const handleOptionClick = (id) => {
        setSelectedOption(id);

        fetch(`/api/polls/${poll.id}/vote`, {
            method: 'POST',
            credentials: 'include',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify({ option_id: id }),
        })
            .then((response) => response.json())
            .then(({ data }) => {})
            .catch((error) => {
                console.error('Error voting:', error);
            });
    };

    const handleDataUpdate = (data, subscribed = false) => {
        setTotalVotes(data.total_votes);
        setPoll(data.poll);
        setOptions(data.options);

        if (subscribed) return;

        setSelectedOption(data.given_vote?.option_id ?? null);
    };

    useEffect(() => {
        const pollId = window.location.pathname.split('/').pop();

        fetch(`/api/polls/${pollId}`)
            .then((response) => response.json())
            .then(({ data }) => handleDataUpdate(data))
            .catch((error) => {
                console.error('Error fetching poll data:', error);
            });
    }, []);

    useEffect(() => {
        if (!poll) return;

        window.Echo.channel(`poll.${poll?.id}`).listen('VoteUpdated', (data) => {
            handleDataUpdate(data, true);
        });

        return () => {
            window.Echo.leave(`poll.${poll?.id}`);
        };
    }, [poll?.id]);

    return (
        <div className="min-h-screen bg-gray-100 flex flex-col items-center p-4 pt-20">
            <nav className="w-full max-w-md  bg-blue-100 rounded-lg shadow-lg p-4 mb-6">
                <div className="flex justify-between items-center">
                    <h1 className="text-xl font-bold text-gray-800">Poll Dashboard</h1>
                    <Link
                        to="/"
                        className="text-blue-500 hover:text-blue-700 font-medium"
                    >
                        See All Available Polls
                    </Link>
                </div>
            </nav>

            <div className="w-full max-w-md bg-white rounded-lg shadow-lg p-6">
                <header className="text-center mb-6">
                    <h1 className="text-2xl font-bold text-gray-800">{poll?.question}</h1>
                    <p className="text-sm text-gray-600 mt-2">
                        {selectedOption ? 'You have already voted.' : 'Select an option below to vote.'}
                    </p>
                </header>

                <div className="space-y-4">
                    {options.map((option) => (
                        <div key={option.id}>
                            <input
                                type="checkbox"
                                name="poll"
                                id={option.id}
                                checked={selectedOption === option.id}
                                className="hidden"
                                onChange={() => handleOptionClick(option.id)}
                            />
                            <label
                                htmlFor={option.id}
                                className={`block p-4 rounded-lg cursor-pointer transition-all duration-300 ${
                                    selectedOption === option.id
                                        ? 'border-2 border-blue-500 bg-blue-100'
                                        : 'border-2 border-gray-200 hover:border-blue-500 hover:bg-gray-50'
                                }`}
                            >
                                <div className="flex justify-between items-center">
                                    <div className="flex items-center gap-3">
                                        <span
                                            className={`w-5 h-5 rounded-full border-2 ${
                                                selectedOption === option.id
                                                    ? 'border-blue-300'
                                                    : 'border-gray-300'
                                            }`}
                                            style={{
                                                backgroundColor:
                                                    selectedOption === option.id ? '#1a48af' : 'transparent',
                                            }}
                                        ></span>
                                        <span className="text-gray-800 font-medium">
                                            {option.title} ({option.votes} votes)
                                        </span>
                                    </div>
                                    <span className="text-gray-600">{option.percentage}%</span>
                                </div>
                                <div className="mt-3 h-2 rounded-full bg-gray-200 w-full">
                                    <div
                                        className={`mt-3 h-2 rounded-full bg-blue-500`}
                                        style={{ width: `${option.percentage}%` }}
                                    ></div>
                                </div>
                            </label>
                        </div>
                    ))}
                </div>

                <footer className="mt-6 text-center text-sm text-gray-500">
                    <p>
                        {totalVotes} people have voted. <b>Results are updated in real-time.</b>
                    </p>
                </footer>
            </div>
        </div>
    );
};

export default Poll;
