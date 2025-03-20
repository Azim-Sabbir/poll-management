import React, { useEffect, useState } from 'react';
import { Link } from "react-router-dom";

const PollsList = () => {
    const [resources, setResources] = useState([]);

    useEffect(() => {
        fetch(`/polls`)
            .then(response => response.json())
            .then(data => {
                setResources(data);
            })
            .catch(error => {
                console.error('Error fetching poll data:', error);
            });
    }, []);

    return (
        <div className="min-h-screen bg-gray-100 flex items-center justify-center p-4">
            <div className="w-full max-w-2xl bg-white rounded-lg shadow-lg p-6">
                <header className="text-center mb-6">
                    <h1 className="text-2xl font-bold text-gray-800">All Polls</h1>
                    <p className="text-sm text-gray-600 mt-2">
                        Browse through the list of polls and vote for your favorite options.
                    </p>
                </header>

                <div className="space-y-4">
                    {resources.map((resource) => (
                        <div
                            key={resource.poll.id}
                            className="flex items-center justify-between p-6 rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-300"
                        >
                            <div>
                                <h2 className="text-xl font-semibold text-gray-800">{resource.poll.question}</h2>
                                <p className="text-sm text-gray-600 mt-2">
                                    {resource.total_votes} votes · {resource.options.length} options
                                </p>
                            </div>
                            <Link
                                to={`/${resource.poll.slug}`}
                                className="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors duration-300"
                            >
                                Participate <b> {"→"}</b>
                            </Link>
                        </div>
                    ))}
                </div>
            </div>
        </div>
    );
};

export default PollsList;
